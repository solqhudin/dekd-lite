<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Left: Logo + Main Nav --}}
            <div class="flex items-center gap-8">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-8 w-auto text-orange-500" />
                        <span class="hidden sm:inline text-sm font-semibold tracking-wide text-gray-800">
                            Dekd Lite
                        </span>
                    </a>
                </div>

                {{-- Desktop Nav Links --}}
                <div class="hidden sm:flex items-center gap-6 text-sm font-medium">
                    {{-- หน้าแรก --}}
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center border-b-2 px-1.5 pb-1
                              {{ request()->routeIs('home') ? 'border-orange-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-900 hover:border-gray-200' }}">
                        หน้าแรก
                    </a>

                    {{-- กระทู้ --}}
                    <a href="{{ route('posts.index') }}"
                       class="inline-flex items-center border-b-2 px-1.5 pb-1
                              {{ request()->routeIs('posts.*') ? 'border-orange-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-900 hover:border-gray-200' }}">
                        กระทู้
                    </a>

                    {{-- แอดมิน เฉพาะคนมี role:admin --}}
                    @auth
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.posts.index') }}"
                               class="inline-flex items-center border-b-2 px-1.5 pb-1 gap-1.5
                                      {{ request()->routeIs('admin.*') ? 'border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:text-indigo-600 hover:border-indigo-200' }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                แอดมิน
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Right: User menu (Desktop) --}}
            <div class="hidden sm:flex items-center gap-3">

                @auth
                    {{-- ปุ่มตั้งกระทู้ด่วน --}}
                    <a href="{{ route('posts.create') }}"
                       class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full
                              bg-orange-500 text-white hover:bg-orange-600 shadow-sm">
                        + ตั้งกระทู้ใหม่
                    </a>

                    {{-- Dropdown โปรไฟล์ --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                                       bg-gray-50 border border-gray-200 text-xs text-gray-700
                                       hover:bg-gray-100 focus:outline-none">
                                <div
                                    class="flex items-center justify-center h-6 w-6 rounded-full bg-orange-500 text-white text-[10px] font-semibold">
                                    {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="max-w-[110px] truncate">
                                    {{ Auth::user()->name }}
                                </div>
                                <svg class="h-3 w-3 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                          clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                โปรไฟล์
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    ออกจากระบบ
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                       class="text-xs font-medium text-gray-600 hover:text-gray-900">
                        ล็อกอิน
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-3 py-1.5 text-xs font-semibold rounded-full border border-orange-400 text-orange-600 hover:bg-orange-50">
                        สมัครสมาชิก
                    </a>
                @endguest
            </div>

            {{-- Hamburger (Mobile) --}}
            <div class="flex items-center sm:hidden">
                @auth
                    <a href="{{ route('posts.create') }}"
                       class="mr-2 inline-flex items-center px-2 py-1 text-[10px] rounded-full bg-orange-500 text-white">
                        + กระทู้
                    </a>
                @endauth

                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md
                               text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': ! open, 'inline-flex': open }"
                              class="hidden"
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
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                หน้าแรก
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*')">
                กระทู้
            </x-responsive-nav-link>

            @auth
                @if(auth()->user()->hasRole('admin'))
                    <x-responsive-nav-link :href="route('admin.posts.index')" :active="request()->routeIs('admin.*')">
                        แอดมิน
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        {{-- Mobile: User section --}}
        @auth
            <div class="pt-3 pb-4 border-t border-gray-100">
                <div class="px-4 mb-2">
                    <div class="font-semibold text-sm text-gray-900">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ Auth::user()->email }}
                    </div>
                </div>

                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('posts.create')">
                        + ตั้งกระทู้ใหม่
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('profile.edit')">
                        โปรไฟล์
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            ออกจากระบบ
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth

        @guest
            <div class="pt-3 pb-4 border-t border-gray-100">
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        ล็อกอิน
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        สมัครสมาชิก
                    </x-responsive-nav-link>
                </div>
            </div>
        @endguest
    </div>
</nav>
