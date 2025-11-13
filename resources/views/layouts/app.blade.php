<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Wardes Shop')</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Custom Styles --}}
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Dropdown Menu Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            margin-top: 8px;
            min-width: 192px;
            background-color: white;
            border-radius: 6px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            display: block;
            width: 100%;
            padding: 8px 16px;
            text-align: left;
            color: #374151;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background-color: #f3f4f6;
        }

        .dropdown-toggle {
            cursor: pointer;
            background: none;
            border: none;
            display: flex;
            align-items: center;
            color: #374151;
            font-size: 14px;
            padding: 0;
        }

        .dropdown-toggle:hover {
            color: #2563eb;
        }

        .dropdown-toggle svg {
            width: 16px;
            height: 16px;
            margin-left: 4px;
            transition: transform 0.2s;
        }

        .dropdown-toggle.active svg {
            transform: rotate(180deg);
        }

        /* Overlay untuk menutup dropdown saat klik di luar */
        .dropdown-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 999;
        }

        .dropdown-overlay.show {
            display: block;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100">
    {{-- Navbar --}}
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                {{-- Logo/Brand --}}
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-600">
                        🛒 Wardes
                    </a>
                </div>

                {{-- Navigation Menu --}}
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 flex items-center">
                        Keranjang
                        <span id="cart-count-badge"
                            class="ml-2 bg-red-500 text-white rounded-full px-2 py-1 text-xs">3</span>
                    </a>


                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-700 hover:text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}


    {{-- Scripts --}}
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Dropdown menu toggle
        const dropdownBtn = document.getElementById('user-dropdown-btn');
        const dropdownMenu = document.getElementById('user-dropdown-menu');
        const dropdownOverlay = document.getElementById('dropdown-overlay');

        if (dropdownBtn && dropdownMenu && dropdownOverlay) {
            // Toggle dropdown saat button diklik
            dropdownBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                const isShowing = dropdownMenu.classList.contains('show');

                if (isShowing) {
                    dropdownMenu.classList.remove('show');
                    dropdownOverlay.classList.remove('show');
                    dropdownBtn.classList.remove('active');
                } else {
                    dropdownMenu.classList.add('show');
                    dropdownOverlay.classList.add('show');
                    dropdownBtn.classList.add('active');
                }
            });

            // Tutup dropdown saat klik overlay
            dropdownOverlay.addEventListener('click', function () {
                dropdownMenu.classList.remove('show');
                dropdownOverlay.classList.remove('show');
                dropdownBtn.classList.remove('active');
            });

            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function (e) {
                if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                    dropdownOverlay.classList.remove('show');
                    dropdownBtn.classList.remove('active');
                }
            });
        }


    </script>

    @stack('scripts')
</body>

</html>