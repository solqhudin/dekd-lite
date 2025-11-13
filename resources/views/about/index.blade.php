@extends('layouts.navigation')
@section('title', 'เกี่ยวกับเรา • Engenius Group')

@section('content')
@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;

    $has = isset($about) && $about;

    // แปลงค่าเป็นอาร์เรย์อย่างปลอดภัย (รองรับทั้ง JSON และข้อความหลายบรรทัด)
    $toArray = function ($v) {
        if (!$v) return [];
        if (is_array($v)) return $v;
        $json = json_decode($v, true);
        if (is_array($json)) return $json;
        return collect(preg_split("/\r\n|\n|\r/", (string)$v))
            ->map(fn($s)=>trim($s))->filter()->values()->all();
    };

    $affiliates = collect($toArray($has ? $about->affiliates ?? null : null));
    $programs   = collect($toArray($has ? $about->programs   ?? null : null));
    $team       = collect($toArray($has ? $about->team       ?? null : null));
    $faqs       = collect($toArray($has ? $about->faqs       ?? null : null));

    // ใหม่: ค่านิยมและช่องทางติดต่อเสริม (แบบข้อความบรรทัดละรายการ)
    $values     = collect($toArray($has ? $about->values_text   ?? null : null));
    $contactsEx = collect($toArray($has ? $about->contacts_text ?? null : null));

    // แยก "หัวข้อ — คำอธิบาย" จากบรรทัดเดียว
    $splitLine = function ($line) {
        if (is_array($line)) return $line; // รองรับโครงสร้าง {title,desc}
        $parts = preg_split('/\s[—\-]\s|—|\-/', $line, 2);
        return ['title' => trim($parts[0] ?? $line), 'desc' => trim($parts[1] ?? '')];
    };

    // แปลงรายการคอนแทคเสริม -> {label, value, href?}
    $contactsList = $contactsEx->map(function ($row) {
        if (is_array($row)) {
            $label = $row['label'] ?? null;
            $value = $row['value'] ?? null;
        } else {
            if (preg_match('/^(.+?)\s*[—:]\s*(.+)$/u', $row, $m)) {
                $label = trim($m[1]); $value = trim($m[2]);
            } else {
                $label = null; $value = trim($row);
            }
        }
        $href = Str::startsWith((string)$value, ['http://','https://']) ? $value : null;
        return compact('label','value','href');
    });

    // ✅ ใช้ hero_image_url เพียงคอลัมน์เดียว
    // ถ้าเป็น http/https ให้ใช้ตรงๆ, ถ้าเป็นพาธไฟล์ให้ Storage::url()
    $heroSrc = null;
    if ($has && !empty($about->hero_image_url)) {
        $v = (string) $about->hero_image_url;
        $heroSrc = Str::startsWith($v, ['http://','https://']) ? $v : Storage::url($v);
    }

    // ✅ แผนที่จาก map_query — ทำให้เต็มบล็อกแบบ responsive (16:9)
    $mapIframe = function($q) {
        if (!$q) return null;
        $qq = urlencode($q);
        return '
        <div class="relative w-full" style="padding-top:56.25%;">
            <iframe
                src="https://www.google.com/maps?q='.$qq.'&output=embed"
                class="absolute inset-0 w-full h-full block border-0"
                style="border:0"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen
            ></iframe>
        </div>';
    };
@endphp

<div class="max-w-6xl mx-auto px-4 py-10 space-y-12">

    @unless($has)
        <div class="rounded-2xl border border-yellow-200 bg-yellow-50 text-yellow-800 px-5 py-4 text-sm">
            ยังไม่มีข้อมูล “เกี่ยวกับเรา” ในระบบขณะนี้
            @auth
                @if(auth()->user()->hasRole('admin'))
                    — <a class="underline font-medium" href="{{ route('admin.about.create') }}">เพิ่มข้อมูลเกี่ยวกับเรา</a>
                @endif
            @endauth
        </div>
    @endunless

    {{-- HERO: ข้อความ + รูป (ถ้ามี) --}}
    <section class="rounded-2xl bg-white border border-gray-100 p-6 md:p-8 shadow-sm">
        <div class="grid md:grid-cols-2 gap-6 items-center">
            {{-- ข้อความ --}}
            <div>
                <div class="inline-flex items-center gap-2 text-xs font-semibold text-brand-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-700"></span>
                    {{ $has ? ($about->hero_badge ?: 'ABOUT US') : 'ABOUT US' }}
                </div>

                <h1 class="mt-2 text-3xl md:text-4xl font-extrabold leading-tight text-gray-900">
                    {{ $has ? ($about->hero_title_th ?: ($about->hero_title_en ?: 'Engenius Group')) : 'Engenius Group' }}
                </h1>

                @if($has && ($about->hero_intro_th || $about->hero_intro_en))
                    <p class="mt-3 text-gray-600">
                        {{ $about->hero_intro_th ?: $about->hero_intro_en }}
                    </p>
                @endif

                {{-- ปุ่มติดต่อแบบข้อความ --}}
                <div class="mt-5 flex flex-wrap gap-3 text-sm">
                    @if($has && $about->phone)
                        <a href="tel:{{ $about->phone }}" class="inline-flex items-center px-4 py-2 rounded-full border text-gray-700 hover:bg-gray-50">
                            โทร: {{ $about->phone }}
                        </a>
                    @endif
                    @if($has && $about->email)
                        <a href="mailto:{{ $about->email }}" class="inline-flex items-center px-4 py-2 rounded-full border text-gray-700 hover:bg-gray-50">
                            อีเมล: {{ $about->email }}
                        </a>
                    @endif
                    @if($has && $about->line_url)
                        <a href="{{ $about->line_url }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 rounded-full bg-brand-700 text-white hover:brightness-110">
                            LINE
                        </a>
                    @endif
                </div>

                {{-- ที่อยู่ / แผนที่ย่อ --}}
                @if($has && ($about->address || $about->map_query))
                    <div class="mt-5 grid md:grid-cols-2 gap-4">
                        @if($about->address)
                            <div class="rounded-xl border border-gray-100 p-4">
                                <div class="text-xs text-gray-500">ที่อยู่</div>
                                <div class="text-gray-800">{{ $about->address }}</div>
                            </div>
                        @endif
                        @if($about->map_query)
                            <div class="rounded-xl overflow-hidden border border-gray-100">
                                <div class="relative w-full" style="padding-top:56.25%;">
                                    <iframe
                                        src="https://www.google.com/maps?q={{ urlencode($about->map_query) }}&output=embed"
                                        class="absolute inset-0 w-full h-full block border-0"
                                        style="border:0"
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"
                                        allowfullscreen
                                    ></iframe>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- รูป --}}
            <div>
                @if($heroSrc)
                    <img
                        src="{{ $heroSrc }}"
                        alt="Engenius Group Hero"
                        class="w-full h-64 md:h-72 rounded-2xl object-cover ring-1 ring-black/10"
                        onerror="this.style.opacity=0.2"
                    />
                @else
                    {{-- ไม่มีภาพ hero --}}
                    <div class="w-full h-64 md:h-72 rounded-2xl bg-gray-100 border border-dashed border-gray-300 flex items-center justify-center text-gray-400">
                        เกี่ยวกับเรา • Engenius Group
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- คำอธิบาย TH / EN --}}
    @if($has && ($about->hero_intro_th || $about->hero_intro_en))
        <section class="grid md:grid-cols-2 gap-6">
            @if($about->hero_intro_th)
                <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900">บริษัท เอ็นจีเนียส กรุ๊ป จำกัด</h2>
                    <div class="mt-3 text-gray-700 leading-relaxed">
                        {!! nl2br(e($about->hero_intro_th)) !!}
                    </div>
                </div>
            @endif
            @if($about->hero_intro_en)
                <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900">ENGENIUS GROUP CO., LTD.</h2>
                    <div class="mt-3 text-gray-700 leading-relaxed">
                        {!! nl2br(e($about->hero_intro_en)) !!}
                    </div>
                </div>
            @endif
        </section>
    @endif

    {{-- วิสัยทัศน์ / พันธกิจ / ค่านิยม --}}
    @if($has && (($about->vision ?? null) || ($about->mission ?? null) || $values->isNotEmpty()))
        <section class="grid md:grid-cols-3 gap-6">
            <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-brand-700">วิสัยทัศน์ (Vision)</h3>
                <p class="mt-2 text-gray-700 leading-relaxed">{!! nl2br(e($about->vision ?? '')) !!}</p>
            </div>
            <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-brand-700">พันธกิจ (Mission)</h3>
                <p class="mt-2 text-gray-700 leading-relaxed">{!! nl2br(e($about->mission ?? '')) !!}</p>
            </div>
            <div class="rounded-2xl bg-white border border-gray-100 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-brand-700">ค่านิยม (Values)</h3>
                @if($values->isNotEmpty())
                    <ul class="mt-2 space-y-2 text-gray-700">
                        @foreach($values as $v)
                            <li class="flex items-start gap-2">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-brand-700"></span>
                                <span>{{ is_array($v) ? ($v['text'] ?? json_encode($v)) : $v }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-2 text-gray-500">—</p>
                @endif
            </div>
        </section>
    @endif

    {{-- บริษัทในเครือ --}}
    @if($affiliates->isNotEmpty())
        <section>
            <h2 class="text-xl font-bold text-gray-900">บริษัทในเครือ</h2>
            <ul class="mt-3 grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($affiliates as $line)
                    <li class="p-4 rounded-2xl bg-white border border-gray-100 shadow-sm text-gray-800">
                        {{ is_array($line) ? ($line['name'] ?? json_encode($line)) : $line }}
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    {{-- โครงการ / Programs --}}
    @if($programs->isNotEmpty())
        <section>
            <h2 class="text-xl font-bold text-gray-900">โครงการ / Programs</h2>
            <div class="mt-4 grid md:grid-cols-2 gap-4">
                @foreach($programs as $p)
                    @php $p = $splitLine($p); @endphp
                    <div class="p-5 rounded-2xl bg-white border border-gray-100 shadow-sm">
                        <div class="font-semibold text-gray-900">{{ $p['title'] ?? '-' }}</div>
                        @if(!empty($p['desc']))
                            <p class="text-sm text-gray-600 mt-1">{{ $p['desc'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ทีมงาน --}}
    @if($team->isNotEmpty())
        <section>
            <h2 class="text-xl font-bold text-gray-900">ทีมผู้บริหาร / ทีมงาน</h2>
            <ul class="mt-3 space-y-2">
                @foreach($team as $t)
                    @php $t = $splitLine($t); @endphp
                    <li class="p-4 rounded-2xl bg-white border border-gray-100 shadow-sm">
                        <div class="font-semibold text-gray-900">{{ $t['title'] ?? '-' }}</div>
                        @if(!empty($t['desc']))
                            <div class="text-sm text-gray-600">{{ $t['desc'] }}</div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    {{-- FAQ --}}
    @if($faqs->isNotEmpty())
        <section>
            <h2 class="text-xl font-bold text-gray-900">คำถามที่พบบ่อย (FAQ)</h2>
            <div class="mt-3 space-y-3">
                @foreach($faqs as $f)
                    @php
                        if (is_array($f)) { $q = $f['q'] ?? ''; $a = $f['a'] ?? ''; }
                        else { $pair = $splitLine($f); $q = $pair['title'] ?? ''; $a = $pair['desc'] ?? ''; }
                    @endphp
                    <div class="rounded-2xl bg-white border border-gray-100 p-5 shadow-sm">
                        <div class="font-semibold text-gray-900">{{ $q }}</div>
                        @if($a)<div class="mt-1 text-gray-700">{{ $a }}</div>@endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ติดต่อเรา + แผนที่ --}}
    @if($has && ($about->address || $about->phone || $about->email || $about->line_url || $contactsList->isNotEmpty() || $about->map_query))
        <section class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 p-6 rounded-2xl bg-white border border-gray-100 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900">ติดต่อเรา</h2>
                <div class="mt-3 space-y-2 text-gray-700">
                    @if($about->address)
                        <div>
                            <div class="text-xs text-gray-500">ที่อยู่</div>
                            <div>{{ $about->address }}</div>
                        </div>
                    @endif
                    @if($about->phone)
                        <div>
                            <div class="text-xs text-gray-500">โทร</div>
                            <div>{{ $about->phone }}</div>
                        </div>
                    @endif
                    @if($about->email)
                        <div>
                            <div class="text-xs text-gray-500">อีเมล</div>
                            <a href="mailto:{{ $about->email }}" class="text-brand-700 hover:underline">{{ $about->email }}</a>
                        </div>
                    @endif
                    @if($about->line_url)
                        <div>
                            <div class="text-xs text-gray-500">LINE</div>
                            <a href="{{ $about->line_url }}" target="_blank" class="text-brand-700 hover:underline">{{ $about->line_url }}</a>
                        </div>
                    @endif

                    {{-- ช่องทางติดต่อเสริมจาก textarea --}}
                    @foreach($contactsList as $c)
                        <div>
                            @if(!empty($c['label']))<div class="text-xs text-gray-500">{{ $c['label'] }}</div>@endif
                            @if(!empty($c['href']))
                                <a href="{{ $c['href'] }}" target="_blank" class="text-brand-700 hover:underline">
                                    {{ $c['value'] }}
                                </a>
                            @elseif(!empty($c['value']))
                                <div>{{ $c['value'] }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-2 rounded-2xl overflow-hidden border border-gray-100 shadow-sm bg-white">
                @if($about->map_query)
                    {!! $mapIframe($about->map_query) !!}
                @else
                    <div class="h-72 w-full bg-gray-100 flex items-center justify-center text-gray-400">ไม่มีแผนที่</div>
                @endif
            </div>
        </section>
    @endif
</div>
@endsection
