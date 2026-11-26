<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title') | Perpustakaan Digital
    </title>


    {{-- =====================================================
         VITE
    ====================================================== --}}

   <script src="{{ asset('js/script.js') }}"></script>


    {{-- =====================================================
         GOOGLE FONT
    ====================================================== --}}

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Nunito:wght@400;600;700;800&family=Fraunces:wght@700;800;900&display=swap"
        rel="stylesheet"
    >


    {{-- =====================================================
         FONT AWESOME
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >


    {{-- =====================================================
         GLOBAL CSS
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/layout/global.css') }}"
    >


    {{-- =====================================================
         LAYOUT CSS
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/layout/layout.css') }}"
    >


    {{-- =====================================================
         SIDEBAR CSS
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/dashboard/siswa.css') }}"
    >


    {{-- =====================================================
         TOPBAR CSS
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/layout/topbar.css') }}"
    >


    {{-- =====================================================
         DASHBOARD SISWA
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/dashboard/siswa.css') }}"
    >


    {{-- =====================================================
         CSS KHUSUS HALAMAN
    ====================================================== --}}

    @yield('styles')
    @stack('styles')

</head>


<body>

<div class="dashboard-wrapper">


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    <aside class="sidebar">


        {{-- LOGO --}}

        <div class="sidebar-logo">

            <div class="logo-icon">
                📚
            </div>

            <div class="logo-text">
                PD
            </div>

        </div>


        <div class="sidebar-line"></div>


        {{-- MENU --}}

        <nav class="sidebar-menu">


            {{-- DASHBOARD --}}

            <a
                href="{{ route('siswa.dashboard') }}"
                class="sidebar-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}"
            >

                <span class="sidebar-icon">
                    🏠
                </span>

                <span class="sidebar-tooltip">
                    Dashboard
                </span>

            </a>


            {{-- KOLEKSI BUKU --}}

            <a
                href="{{ route('siswa.books.index') }}"
                class="sidebar-item {{ request()->routeIs('siswa.books.*') ? 'active' : '' }}"
            >

                <span class="sidebar-icon">
                    🔍
                </span>

                <span class="sidebar-tooltip">
                    Koleksi Buku
                </span>

            </a>


            {{-- HISTORI --}}

            <a
                href="{{ route('siswa.history') }}"
                class="sidebar-item {{ request()->routeIs('siswa.history') ? 'active' : '' }}"
            >

                <span class="sidebar-icon">
                    📋
                </span>

                <span class="sidebar-tooltip">
                    Histori Peminjaman
                </span>

            </a>


            {{-- LEADERBOARD --}}

            <a
                href="{{ route('siswa.leaderboard') }}"
                class="sidebar-item {{ request()->routeIs('siswa.leaderboard') ? 'active' : '' }}"
            >

                <span class="sidebar-icon">
                    🏆
                </span>

                <span class="sidebar-tooltip">
                    Leaderboard
                </span>

            </a>

        </nav>


        {{-- =====================================================
             BOTTOM SIDEBAR
        ====================================================== --}}

        <div class="sidebar-bottom">

            <div class="sidebar-line"></div>


            {{-- NOTIFIKASI --}}

            <a
                href="{{ route('siswa.notifikasi') }}"
                class="sidebar-item"
            >

                <span class="sidebar-icon">

                    🔔

                    @if($unreadCount > 0)

                        <span class="notification-badge">

                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}

                        </span>

                    @endif

                </span>

            </a>


            {{-- PROFILE --}}

            <a
                href="{{ route('siswa.account') }}"
                class="profile-button"
            >

                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}

                <span class="online-dot"></span>

                <span class="sidebar-tooltip bottom-tooltip">
                    Akun Saya
                </span>

            </a>


            {{-- LOGOUT --}}

            <form
                action="{{ route('logout') }}"
                method="POST"
                class="logout-form"
            >

                @csrf

                <button
                    type="submit"
                    class="logout-button"
                >

                    <span class="logout-icon">
                        ↪
                    </span>

                    <span class="sidebar-tooltip bottom-tooltip">
                        Logout
                    </span>

                </button>

            </form>

        </div>

    </aside>


    {{-- =====================================================
         CONTENT
    ====================================================== --}}

    <main class="content">


        {{-- =================================================
             TOPBAR
        ================================================== --}}

        <header class="topbar">

            <div class="topbar-left">

                <h1 class="topbar-title">
                    @yield('title')
                </h1>

                <div class="topbar-subtitle">
                    Sistem Informasi Perpustakaan Digital
                </div>

            </div>


            <div class="topbar-right">

                <div class="topbar-profile">

                    <div class="topbar-avatar">

                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                    </div>


                    <div class="topbar-user">

                        <strong>
                            {{ Auth::user()->name }}
                        </strong>

                        <span>
                            Siswa
                        </span>

                    </div>

                </div>

            </div>

        </header>


        {{-- =================================================
             PAGE CONTENT
        ================================================== --}}

        <section class="page-content">

            @yield('content')

        </section>


    </main>

</div>


{{-- =========================================================
     JAVASCRIPT KHUSUS HALAMAN
========================================================= --}}

@stack('scripts')


</body>

</html>