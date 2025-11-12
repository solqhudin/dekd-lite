<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title','Engenius Group')</title>
  <meta name="description" content="Engenius Group Portal" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ["Kanit","ui-sans-serif","system-ui"] },
          colors: {
            brand: {
              50:'#f2f3ff',100:'#e6e8ff',200:'#c7ccff',300:'#a4adff',
              400:'#6f7dff',500:'#3a4bff',600:'#172bd6',
              700:'#020263',800:'#010149',900:'#00012f'
            }
          },
          boxShadow: { card:'0 30px 60px -20px rgba(16,24,40,.25)' }
        }
      }
    }
  </script>
  <style>body{font-family:Kanit,ui-sans-serif,system-ui}</style>
  @stack('head')
</head>

<body class="min-h-screen flex flex-col bg-[#f6f7fb]">
  <nav x-data="{ open:false }" class="bg-white/95 backdrop-blur border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        {{-- Left: Logo + Main Nav (Desktop) --}}
        <div class="flex items-center gap-8">
          {{-- Logo --}}
          <div class="shrink-0 flex items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
              {{-- ใช้ component หรือรูปภาพ เลือกอย่างใดอย่างหนึ่ง --}}
              {{-- <x-application-logo class="block h-8 w-auto text-orange-500" /> --}}
              <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" alt="Logo">
              <span class="hidden sm:inline text-sm font-semibold tracking-wide text-gray-800">
                Dek-Due
              </span>
            </a>
          </div>

          {{-- Desktop Nav Links --}}
          <div class="hidden sm:flex items-center gap-6 text-sm font-medium">
            <a href="{{ route('home') }}"
               class="inline-flex items-center border-b-2 px-1.5 pb-1
                 {{ request()->routeIs('home') ? 'border-brand-700 text-brand-700' : 'border-transparent text-gray-500 hover:text-gray-900 hover:border-gray-200' }}">
              หน้าแรก
            </a>

            <a href="{{ route('posts.index') }}"
               class="inline-flex items-center border-b-2 px-1.5 pb-1
                 {{ request()->routeIs('posts.*') ? 'border-brand-700 text-brand-700' : 'border-transparent text-gray-500 hover:text-gray-900 hover:border-gray-200' }}">
              กระทู้
            </a>

            <a href="{{ route('announcements.index') }}"
               class="inline-flex items-center border-b-2 px-1.5 pb-1
                 {{ request()->routeIs('announcements.*') ? 'border-brand-700 text-brand-700' : 'border-transparent text-gray-500 hover:text-gray-900 hover:border-gray-200' }}">
              ประชาสัมพันธ์
            </a>

            <a href="#" class="inline-flex items-center border-b-2 px-1.5 pb-1
                 {{ request()->routeIs('about') ? 'border-brand-700 text-brand-700' : 'border-transparent text-gray-500 hover:text-gray-900 hover:border-gray-200' }}">
              เกี่ยวกับเรา
            </a>

            <a href="#" class="inline-flex items-center border-b-2 px-1.5 pb-1
                 {{ request()->routeIs('contact') ? 'border-brand-700 text-brand-700' : 'border-transparent text-gray-500 hover:text-gray-900 hover:border-gray-200' }}">
              ติดต่อเรา
            </a>

            @auth
              @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.posts.index') }}"
                   class="inline-flex items-center border-b-2 px-1.5 pb-1 gap-1.5
                     {{ request()->routeIs('admin.*') ? 'border-brand-700 text-brand-700' : 'border-transparent text-gray-500 hover:text-brand-700/80 hover:border-brand-700/30' }}">
                  <span class="h-1.5 w-1.5 rounded-full bg-brand-700"></span>
                  แอดมิน
                </a>
              @endif
            @endauth
          </div>
        </div>

        {{-- Right: User menu (Desktop) --}}
        <div class="hidden sm:flex items-center gap-3">
          @auth
            <a href="{{ route('posts.create') }}"
               class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full bg-brand-700 text-white hover:brightness-110 shadow-sm">
              + ตั้งกระทู้ใหม่
            </a>

            <x-dropdown align="right" width="48">
              <x-slot name="trigger">
                <button
                  class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-50 border border-gray-200 text-xs text-gray-700 hover:bg-gray-100 focus:outline-none">
                  <div class="flex items-center justify-center h-6 w-6 rounded-full bg-brand-700 text-white text-[10px] font-semibold">
                    {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                  </div>
                  <div class="max-w-[110px] truncate">{{ Auth::user()->name }}</div>
                  <svg class="h-3 w-3 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                          clip-rule="evenodd" />
                  </svg>
                </button>
              </x-slot>

              <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')">โปรไฟล์</x-dropdown-link>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    ออกจากระบบ
                  </button>
                </form>
              </x-slot>
            </x-dropdown>
          @endauth

          @guest
            <a href="{{ route('login') }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">ล็อกอิน</a>
            <a href="{{ route('register') }}" class="px-3 py-1.5 text-xs font-semibold rounded-full border border-brand-700 text-brand-700 hover:bg-brand-700/5">
              สมัครสมาชิก
            </a>
          @endguest
        </div>

        {{-- Hamburger (Mobile) --}}
        <div class="flex items-center sm:hidden">
          @auth
            <a href="{{ route('posts.create') }}"
               class="mr-2 inline-flex items-center px-2 py-1 text-[10px] rounded-full bg-brand-700 text-white">
              + กระทู้
            </a>
          @endauth
          <button @click="open = ! open"
                  class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path :class="{ 'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
              <path :class="{ 'hidden': ! open, 'inline-flex': open }" class="hidden"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    {{-- Responsive Menu (Mobile) --}}
    <div :class="{ 'block': open, 'hidden': ! open }" class="hidden sm:hidden border-t border-gray-100 bg-white">
      <div class="pt-3 pb-2 space-y-1">
        <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">หน้าแรก</x-responsive-nav-link>
        <x-responsive-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*')">กระทู้</x-responsive-nav-link>
        <x-responsive-nav-link :href="route('announcements.index')" :active="request()->routeIs('announcements.*')">ประชาสัมพันธ์</x-responsive-nav-link>
        @auth
          @if(auth()->user()->hasRole('admin'))
            <x-responsive-nav-link :href="route('admin.posts.index')" :active="request()->routeIs('admin.*')">แอดมิน</x-responsive-nav-link>
          @endif
        @endauth
      </div>

      @auth
        <div class="pt-3 pb-4 border-t border-gray-100">
          <div class="px-4 mb-2">
            <div class="font-semibold text-sm text-gray-900">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
          </div>
          <div class="space-y-1">
            <x-responsive-nav-link :href="route('posts.create')">+ ตั้งกระทู้ใหม่</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.edit')">โปรไฟล์</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                ออกจากระบบ
              </button>
            </form>
          </div>
        </div>
      @endauth

      @guest
        <div class="pt-3 pb-4 border-t border-gray-100">
          <div class="space-y-1">
            <x-responsive-nav-link :href="route('login')">ล็อกอิน</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register')">สมัครสมาชิก</x-responsive-nav-link>
          </div>
        </div>
      @endguest
    </div>
  </nav>

  <main class="flex-1">
    @yield('content')
  </main>

  <footer class="py-6 text-center text-xs text-gray-500">
    © <span id="y"></span> Engenius Group — All rights reserved
  </footer>

  <script>document.getElementById('y').textContent = new Date().getFullYear();</script>
  @stack('scripts')
</body>
</html>
