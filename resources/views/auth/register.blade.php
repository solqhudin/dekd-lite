@extends('layouts.navigation')
@section('title','สมัครสมาชิก • Engenius Group')

@section('content')
  <div class="flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-6xl rounded-[24px] overflow-hidden bg-white shadow-card ring-1 ring-black/5 grid lg:grid-cols-2">

      {{-- ซ้าย: พาเนลสีทึบ #020263 (ไม่มีไล่สี) + ข้อความต้อนรับ --}}
      <section class="relative min-h-[460px] p-10 flex items-center bg-brand-700">
        <div class="pointer-events-none absolute -top-10 -left-10 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-10 right-10 h-52 w-52 rounded-full bg-white/10 blur-2xl"></div>

        <div class="relative z-10 text-white">
          <div class="flex items-center gap-3 mb-6">
            <img src="{{ asset('images/logo.png') }}" class="h-9 w-auto object-contain" alt="Engenius Group">
            <span class="text-sm tracking-wide text-white/90">Engenius Group</span>
          </div>

          <h1 class="text-4xl md:text-5xl font-extrabold leading-tight drop-shadow-sm">สร้างบัญชีใหม่</h1>
          <p class="mt-3 max-w-md text-white/90">
            ลงทะเบียนเพื่อเริ่มใช้งานพอร์ทัล จัดการเครือข่าย คลาวด์ และ IoT ได้สะดวก ปลอดภัย และมีประสิทธิภาพ
          </p>

          <div class="mt-8 flex items-center gap-3 text-xs text-white/80">
            <span class="inline-flex items-center gap-1"><span class="h-1.5 w-1.5 rounded-full bg-white/80"></span> Single Sign-On</span>
            <span class="inline-flex items-center gap-1"><span class="h-1.5 w-1.5 rounded-full bg-white/80"></span> Security First</span>
            <span class="inline-flex items-center gap-1"><span class="h-1.5 w-1.5 rounded-full bg-white/80"></span> 24/7 Access</span>
          </div>
        </div>
      </section>

      {{-- ขวา: การ์ดฟอร์มสมัครสมาชิก --}}
      <section class="bg-white p-8 md:p-10">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="text-lg font-bold text-gray-900">REGISTER</h2>
            <p class="text-sm text-gray-600 mt-1">กรอกข้อมูลให้ครบถ้วนเพื่อเริ่มต้นใช้งาน</p>
          </div>
          <a href="{{ route('login') }}"
             class="hidden md:inline-flex items-center px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-700 hover:border-brand-700/50">
            มีบัญชีอยู่แล้ว? เข้าสู่ระบบ
          </a>
        </div>

        {{-- แจ้งเตือนรวมข้อผิดพลาด --}}
        @if ($errors->any())
          <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            กรุณาตรวจสอบข้อมูลที่กรอกให้ถูกต้อง
          </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="mt-6 grid gap-5">
          @csrf

          {{-- Name --}}
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">ชื่อ-นามสกุล</label>
            <div class="mt-1">
              <input id="name" name="name" type="text" value="{{ old('name') }}" required
                     class="w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50
                            focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none transition">
            </div>
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          {{-- Email --}}
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
            <div class="mt-1">
              <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                     class="w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50
                            focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none transition">
            </div>
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          {{-- Password + toggle --}}
          <div>
            <div class="flex items-center justify-between">
              <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
              <span id="pwStrengthLabel" class="text-xs text-gray-500"></span>
            </div>
            <div class="mt-1 relative">
              <input id="password" name="password" type="password" required autocomplete="new-password"
                     class="w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50 pr-12
                            focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none transition">
              <button type="button" id="togglePassword"
                      class="absolute inset-y-0 right-0 mr-2 grid place-items-center px-2 rounded-full
                             text-gray-400 hover:text-gray-600 focus:outline-none"
                      aria-label="แสดง/ซ่อนรหัสผ่าน">
                <svg id="eyeIcon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            {{-- แถบวัดความแข็งแรงรหัสผ่าน (เบาๆ) --}}
            <div class="mt-2 h-1.5 w-full rounded-full bg-gray-100 overflow-hidden">
              <div id="pwStrengthBar" class="h-full w-0 rounded-full bg-brand-700 transition-all"></div>
            </div>
            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          {{-- Confirm --}}
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">ยืนยันรหัสผ่าน</label>
            <div class="mt-1">
              <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                     class="w-full rounded-2xl border border-gray-200 px-4 py-3 bg-gray-50
                            focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20 outline-none transition">
            </div>
            @error('password_confirmation') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          {{-- Terms --}}
          <label class="inline-flex items-start gap-2 text-sm text-gray-700">
            <input type="checkbox" required class="mt-[3px] rounded border-gray-300 text-brand-700 focus:ring-brand-700">
            <span>
              เมื่อสมัครสมาชิก แสดงว่าคุณยอมรับ
              <a href="#" class="text-brand-700 hover:text-brand-700/80">ข้อกำหนดการใช้งาน</a>
            </span>
          </label>

          {{-- Actions --}}
          <div class="flex flex-wrap items-center gap-3 pt-1">
            <button
              class="px-6 py-3 rounded-full bg-brand-700 text-white hover:brightness-110 shadow-sm
                     focus:outline-none focus:ring-2 focus:ring-brand-700/30">
              สมัครสมาชิก
            </button>
            <a href="{{ route('login') }}"
               class="px-6 py-3 rounded-full border border-gray-300 text-gray-700 hover:border-brand-700/50">
              เข้าสู่ระบบ
            </a>
          </div>
        </form>
      </section>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // Toggle show/hide password
  (function() {
    const btn = document.getElementById('togglePassword');
    const input = document.getElementById('password');
    const eye = document.getElementById('eyeIcon');
    if(btn && input && eye){
      btn.addEventListener('click', () => {
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        eye.innerHTML = isPass
          ? '<path d="M3 3l18 18" stroke-linecap="round"/><path d="M2 12s3.5-7 10-7a10.7 10.7 0 0 1 4.4.9"/><path d="M21.8 13.6C20.7 15.3 17.5 19 12 19a11 11 0 0 1-4.2-.8"/>'
          : '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>';
      });
    }
  })();

  // Password strength (ง่ายๆ เพื่อ UI feedback)
  (function() {
    const pw = document.getElementById('password');
    const bar = document.getElementById('pwStrengthBar');
    const label = document.getElementById('pwStrengthLabel');
    if(!pw || !bar || !label) return;

    const calc = (v) => {
      let s = 0;
      if(v.length >= 8) s++;
      if(/[A-Z]/.test(v)) s++;
      if(/[a-z]/.test(v)) s++;
      if(/[0-9]/.test(v)) s++;
      if(/[^A-Za-z0-9]/.test(v)) s++;
      return Math.min(s, 5);
    };

    pw.addEventListener('input', () => {
      const score = calc(pw.value);
      const pct = [0, 25, 45, 65, 85, 100][score];
      bar.style.width = pct + '%';
      label.textContent = score <= 1 ? 'รหัสผ่านอ่อน' : score <=3 ? 'ปานกลาง' : 'แข็งแรง';
      label.className = 'text-xs ' + (score <=1 ? 'text-red-600' : score<=3 ? 'text-amber-600' : 'text-emerald-600');
    });
  })();
</script>
@endpush