@extends('layouts.navigation')
@section('title','เข้าสู่ระบบ • Engenius Group')

@section('content')
  <div class="flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-6xl rounded-[24px] overflow-hidden bg-white shadow-card ring-1 ring-black/5 grid lg:grid-cols-2">

      {{-- ซ้าย: พาเนลสีทึบ #020263 (ไม่มีไล่สี) --}}
      <section class="relative min-h-[460px] p-10 flex items-center bg-brand-700">
  {{-- แสงจาง ๆ (ยังเป็นสีเดียว ไม่ใช่ไล่สี) --}}
  <div class="pointer-events-none absolute -top-10 -left-10 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
  <div class="pointer-events-none absolute bottom-10 right-10 h-52 w-52 rounded-full bg-white/10 blur-2xl"></div>

  <div class="relative z-10 text-white w-full">
    {{-- โลโก้กึ่งกลาง --}}
   <img
  src="{{ asset('images/logoWhite.png') }}"
  alt="Engenius Group"
  class="h-20 md:h-28 xl:h-32 w-auto object-contain mx-auto select-none"
  loading="eager"
  decoding="async"
  onerror="this.style.opacity=0.3"
/>


    <h1 class="text-4xl md:text-5xl font-extrabold drop-shadow-sm text-center">ยินดีต้อนรับ</h1>
    <p class="mt-3 max-w-md text-white/90 mx-auto text-center">
      เข้าสู่ระบบ Engenius Group Portal เพื่อจัดการโซลูชันเครือข่าย คลาวด์ และ IoT อย่างสะดวก ปลอดภัย และมีประสิทธิภาพ
    </p>
  </div>
</section>



      {{-- ขวา: ฟอร์มเข้าสู่ระบบ --}}
      <section class="bg-white p-8 md:p-10">
        <h2 class="text-lg font-bold text-gray-900">USER LOGIN</h2>

        {{-- สถานะระบบ (เช่น ลิงก์รีเซ็ตรหัสส่งสำเร็จ) --}}
        @if (session('status'))
          <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">
            {{ session('status') }}
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-6 grid gap-4">
          @csrf

          {{-- Email --}}
          <div>
            <label for="email" class="sr-only">อีเมล</label>
            <div class="relative">
              <span class="pointer-events-none absolute inset-y-0 left-0 grid place-items-center pl-3">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path d="M15.5 19.5a4.5 4.5 0 0 0-7 0M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                </svg>
              </span>
              <input
                id="email"
                name="email"
                type="email"
                required
                autofocus
                autocomplete="username"
                value="{{ old('email') }}"
                class="w-full rounded-full border border-gray-200 bg-gray-50 pl-10 pr-3 py-3 outline-none
                       focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20"
                placeholder="อีเมล" />
            </div>
            @error('email')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Password --}}
          <div>
            <label for="password" class="sr-only">รหัสผ่าน</label>
            <div class="relative">
              <span class="pointer-events-none absolute inset-y-0 left-0 grid place-items-center pl-3">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>
                </svg>
              </span>
              <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="current-password"
                class="w-full rounded-full border border-gray-200 bg-gray-50 pl-10 pr-10 py-3 outline-none
                       focus:bg-white focus:border-brand-700 focus:ring-2 focus:ring-brand-700/20"
                placeholder="รหัสผ่าน" />
              <button
                type="button"
                id="togglePassword"
                class="absolute inset-y-0 right-0 mr-2 grid place-items-center px-2 rounded-full
                       text-gray-400 hover:text-gray-600 focus:outline-none"
                aria-label="แสดง/ซ่อนรหัสผ่าน">
                <svg id="eyeIcon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            @error('password')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Remember + Forgot --}}
          <div class="flex items-center justify-between text-sm">
            <label class="inline-flex items-center gap-2 text-gray-700">
              <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-700 focus:ring-brand-700">
              จำฉันไว้
            </label>
            @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="text-brand-700 hover:text-brand-700/80">
                ลืมรหัสผ่าน?
              </a>
            @endif
          </div>

          {{-- ปุ่ม Login (สีทึบ) --}}
          <button
            class="mt-2 inline-flex items-center justify-center rounded-full bg-brand-700 px-6 py-3
                   font-semibold text-white shadow-sm hover:brightness-110
                   focus:outline-none focus:ring-2 focus:ring-brand-700/30">
            LOGIN
          </button>

          {{-- ลิงก์สมัครสมาชิก --}}
          <p class="text-sm text-gray-600">
            ยังไม่มีบัญชี?
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="text-brand-700 hover:text-brand-700/80">สมัครสมาชิก</a>
            @endif
          </p>
        </form>
      </section>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // Toggle show/hide password
  (function () {
    const btn = document.getElementById('togglePassword');
    const input = document.getElementById('password');
    const eye = document.getElementById('eyeIcon');
    if (btn && input && eye) {
      btn.addEventListener('click', () => {
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        eye.innerHTML = isPass
          ? '<path d="M3 3l18 18" stroke-linecap="round"/><path d="M2 12s3.5-7 10-7a10.7 10.7 0 0 1 4.4.9"/><path d="M21.8 13.6C20.7 15.3 17.5 19 12 19a11 11 0 0 1-4.2-.8"/>'
          : '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>';
      });
    }
  })();
</script>
@endpush