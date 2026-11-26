<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Dashboard') | Perpustakaan Digital
    </title>


    {{-- =====================================================
        SCRIPT GLOBAL
    ====================================================== --}}

    <script src="{{ asset('js/script.js') }}"></script>


    {{-- =====================================================
        GOOGLE FONT
    ====================================================== --}}

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
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
        CSS GLOBAL
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/layout/globalp.css') }}"
    >


    {{-- =====================================================
        CSS LAYOUT
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/layout/layoutp.css') }}"
    >


    {{-- =====================================================
        CSS SIDEBAR
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/layout/sidebarp.css') }}"
    >


    {{-- =====================================================
        CSS HAMBURGER PER HALAMAN
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/layout/hamburgerp.css') }}"
    >


    {{-- =====================================================
        CSS TOPBAR
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/layout/topbarp.css') }}"
        
    >
    {{-- =====================================================
        CSS KHUSUS HALAMAN
    ====================================================== --}}

    @yield('styles')

    @stack('styles')


    {{-- =====================================================
        FALLBACK CSS
    ====================================================== --}}

    <style>

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .wrapper {
            min-height: 100vh;
        }

        .main {
            min-height: 100vh;
        }

        .content {
            width: 100%;
            box-sizing: border-box;
        }

        .inventaris-page {
            width: 100%;
            box-sizing: border-box;
        }

    </style>

</head>


<body>


{{-- =====================================================
    SIDEBAR OVERLAY
====================================================== --}}

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
></div>


{{-- =====================================================
    WRAPPER
====================================================== --}}

<div class="wrapper">


    {{-- =================================================
        SIDEBAR
    ================================================== --}}

    <aside
        class="sidebar"
        id="sidebar"
        aria-hidden="false"
    >


      


        {{-- =================================================
            LOGO
        ================================================== --}}

        <div class="logo">

            <div class="logo-icon">
                <i class="fa-solid fa-book-open"></i>
            </div>

            <div class="logo-text">

                <h2>
                    Perpus
                </h2>

                <small>
                    Digital Library
                </small>

            </div>

    {{-- =================================================
        CLOSE
    ================================================== --}}

    <button
        type="button"
        class="sidebar-close"
        id="sidebarClose"
        aria-label="Tutup sidebar"
    >
        <i class="fa-solid fa-xmark"></i>
    </button>

        </div>


        {{-- =================================================
            MENU SIDEBAR
        ================================================== --}}

        <ul class="sidebar-menu">


            {{-- =================================================
                DASHBOARD
            ================================================== --}}

            <li>

                <a
                    href="{{ route('petugas.dashboard') }}"
                    class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}"
                >

                    <i class="fa-solid fa-house"></i>

                    <span>
                        Dashboard
                    </span>

                </a>

            </li>


            {{-- =================================================
                VERIFIKASI ANGGOTA
            ================================================== --}}

            <li>

                <a
                    href="{{ route('petugas.verifikasi') }}"
                    class="{{ request()->routeIs('petugas.verifikasi*') ? 'active' : '' }}"
                >

                    <i class="fa-solid fa-user-check"></i>

                    <span>
                        Verifikasi Anggota
                    </span>

                </a>

            </li>


            {{-- =================================================
                KOLEKSI BUKU
            ================================================== --}}

            <li>

                <a
                    href="{{ route('petugas.books.index') }}"
                    class="{{ request()->routeIs('petugas.books.*') ? 'active' : '' }}"
                >

                    <i class="fa-solid fa-book"></i>

                    <span>
                        Koleksi Buku
                    </span>

                </a>

            </li>


            {{-- =================================================
                PINDAI QR CODE
            ================================================== --}}

            <li>

                <a
                    href="{{ route('petugas.scan') }}"
                    class="{{ request()->routeIs('petugas.scan') ? 'active' : '' }}"
                >

                    <i class="fa-solid fa-qrcode"></i>

                    <span>
                        Pindai QR Code
                    </span>

                </a>

            </li>


            {{-- =================================================
                DAFTAR HADIR
            ================================================== --}}

            <li>

                <a
                    href="{{ route('petugas.attendance.index') }}"
                    class="{{ request()->routeIs('petugas.attendance.*') ? 'active' : '' }}"
                >

                    <i class="fa-solid fa-clipboard-user"></i>

                    <span>
                        Daftar Hadir
                    </span>

                </a>

            </li>


            {{-- =================================================
                ARSIP
            ================================================== --}}

            <li>

                <a
                    href="{{ route('petugas.archive') }}"
                    class="{{ request()->routeIs('petugas.archive*') ? 'active' : '' }}"
                >

                    <i class="fa-solid fa-folder-open"></i>

                    <span>
                        Arsip
                    </span>

                </a>

            </li>


            {{-- =================================================
                AKUN
            ================================================== --}}

            <li>

                <a
                    href="{{ route('petugas.account') }}"
                    class="{{ request()->routeIs('petugas.account') ? 'active' : '' }}"
                >

                    <i class="fa-solid fa-user"></i>

                    <span>
                        Akun
                    </span>

                </a>

            </li>


        </ul>


        {{-- =================================================
            SIDEBAR PROFILE
        ================================================== --}}

        <div class="sidebar-profile">


            {{-- =================================================
                PROFIL PETUGAS
            ================================================== --}}

            <div class="admin-card">

                <div class="avatar">

                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                </div>

                <div class="admin-info">

                    <h4>
                        {{ Auth::user()->name }}
                    </h4>

                    <span>
                        Petugas Senior
                    </span>

                </div>

            </div>


            {{-- =================================================
                STATUS SISTEM
            ================================================== --}}

            <div class="system-status">

                <div class="status-dot"></div>

                <div>

                    <strong>
                        Sistem Aktif
                    </strong>

                    <small>
                        perpus-digital-01
                    </small>

                </div>

            </div>


            {{-- =================================================
                LOGOUT
            ================================================== --}}

            <form
                action="{{ route('logout') }}"
                method="POST"
            >

                @csrf

                <button
                    type="submit"
                    class="logout"
                >

                    <i class="fa-solid fa-right-from-bracket"></i>

                    <span>
                        Logout
                    </span>

                </button>

            </form>


        </div>

    </aside>


    {{-- =====================================================
        MAIN
    ====================================================== --}}

    <main
        class="main"
        id="main"
    >


        {{-- =================================================
            TOPBAR
        ================================================== --}}

        <header class="topbar">
            {{-- =====================================================
     HAMBURGER MENU
===================================================== --}}

<button
    type="button"
    class="page-hamburger"
    id="hamburgerBtn"
    aria-label="Buka menu"
    aria-expanded="false"
>
    <i class="fa-solid fa-bars"></i>
</button>



            {{-- =================================================
                TOPBAR LEFT
            ================================================== --}}

            <div class="topbar-left">

                <h2>
                    @yield('title')
                </h2>

                <p>
                    Sistem Informasi Perpustakaan Digital
                </p>

            </div>


            {{-- =================================================
                TOPBAR RIGHT
            ================================================== --}}

            <div class="topbar-right">


                {{-- =================================================
                    SEARCH
                ================================================== --}}

                <form
                    action="{{ route('petugas.search') }}"
                    method="GET"
                    class="topbar-search-form"
                >

                    <div class="search-box">

                        <i class="fa-solid fa-search"></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari buku atau anggota..."
                            autocomplete="off"
                        >

                    </div>

                </form>


                {{-- =================================================
                    NOTIFICATION
                ================================================== --}}

                <button
                    class="notif"
                    type="button"
                    onclick="window.location='{{ route('petugas.notifikasi') }}'"
                    title="Notifikasi"
                    aria-label="Notifikasi"
                >

                    <i class="fa-solid fa-bell"></i>


                    @if(($unreadCount ?? 0) > 0)

                        <span class="notif-badge">

                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}

                        </span>

                    @endif

                </button>


            </div>

        </header>


        {{-- =================================================
            CONTENT
        ================================================== --}}

        <section class="content">

            @yield('content')

        </section>


    </main>


</div>


{{-- =========================================================
    SCRIPT SIDEBAR
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =====================================================
       ELEMENT
    ===================================================== */

    const sidebar =
        document.getElementById('sidebar');

    const hamburgerBtn =
        document.getElementById('hamburgerBtn');

    const sidebarClose =
        document.getElementById('sidebarClose');

    const sidebarOverlay =
        document.getElementById('sidebarOverlay');


    /* =====================================================
       CEK ELEMENT UTAMA
    ===================================================== */

    if (!sidebar) {
        return;
    }


    /* =====================================================
       CEK DESKTOP / LAPTOP
    ===================================================== */

    function isDesktop() {

        return window.innerWidth >= 993;

    }


    /* =====================================================
       BUKA SIDEBAR
    ===================================================== */

    function openSidebar() {


        /* -----------------------------------------------
           Hapus status tertutup desktop
        ----------------------------------------------- */

        sidebar.classList.remove(
            'desktop-closed'
        );


        /* -----------------------------------------------
           Mobile / Tablet
        ----------------------------------------------- */

        if (!isDesktop()) {

            sidebar.classList.add(
                'show'
            );

            if (sidebarOverlay) {

                sidebarOverlay.classList.add(
                    'show'
                );

            }

            document.body.classList.add(
                'sidebar-open'
            );

        }


        /* -----------------------------------------------
           Hamburger disembunyikan ketika sidebar terbuka
        ----------------------------------------------- */

        if (hamburgerBtn) {

            hamburgerBtn.classList.add(
                'hidden'
            );

            hamburgerBtn.classList.remove(
                'desktop-visible'
            );

            hamburgerBtn.setAttribute(
                'aria-expanded',
                'true'
            );

        }


        /* -----------------------------------------------
           Accessibility
        ----------------------------------------------- */

        sidebar.setAttribute(
            'aria-hidden',
            'false'
        );

    }


    /* =====================================================
       TUTUP SIDEBAR
    ===================================================== */

    function closeSidebar() {


        /* -----------------------------------------------
           DESKTOP / LAPTOP
        ----------------------------------------------- */

        if (isDesktop()) {


            sidebar.classList.add(
                'desktop-closed'
            );


            sidebar.classList.remove(
                'show'
            );


            if (sidebarOverlay) {

                sidebarOverlay.classList.remove(
                    'show'
                );

            }


            document.body.classList.remove(
                'sidebar-open'
            );


            /* -------------------------------------------
               Tampilkan hamburger halaman
            ------------------------------------------- */

            if (hamburgerBtn) {

                hamburgerBtn.classList.remove(
                    'hidden'
                );

                hamburgerBtn.classList.add(
                    'desktop-visible'
                );

                hamburgerBtn.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }


            sidebar.setAttribute(
                'aria-hidden',
                'true'
            );


            return;

        }


        /* -----------------------------------------------
           MOBILE / TABLET
        ----------------------------------------------- */

        sidebar.classList.remove(
            'show'
        );


        sidebar.classList.remove(
            'desktop-closed'
        );


        if (sidebarOverlay) {

            sidebarOverlay.classList.remove(
                'show'
            );

        }


        document.body.classList.remove(
            'sidebar-open'
        );


        /* -----------------------------------------------
           Tampilkan hamburger
        ----------------------------------------------- */

        if (hamburgerBtn) {

            hamburgerBtn.classList.remove(
                'hidden'
            );

            hamburgerBtn.classList.remove(
                'desktop-visible'
            );

            hamburgerBtn.setAttribute(
                'aria-expanded',
                'false'
            );

        }


        sidebar.setAttribute(
            'aria-hidden',
            'true'
        );

    }


    /* =====================================================
       HAMBURGER
    ===================================================== */

    if (hamburgerBtn) {

        hamburgerBtn.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                openSidebar();

            }
        );

    }


    /* =====================================================
       TOMBOL X
    ===================================================== */

    if (sidebarClose) {

        sidebarClose.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                closeSidebar();

            }
        );

    }


    /* =====================================================
       OVERLAY
    ===================================================== */

    if (sidebarOverlay) {

        sidebarOverlay.addEventListener(
            'click',
            function () {

                closeSidebar();

            }
        );

    }


    /* =====================================================
       ESCAPE
    ===================================================== */

    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key !== 'Escape') {
                return;
            }


            if (isDesktop()) {

                if (
                    !sidebar.classList.contains(
                        'desktop-closed'
                    )
                ) {

                    closeSidebar();

                }

            } else {

                if (
                    sidebar.classList.contains(
                        'show'
                    )
                ) {

                    closeSidebar();

                }

            }

        }
    );


    /* =====================================================
       KLIK MENU SIDEBAR
    ===================================================== */

    const menuLinks =
        sidebar.querySelectorAll(
            '.sidebar-menu a'
        );


    menuLinks.forEach(
        function (link) {

            link.addEventListener(
                'click',
                function () {

                    /*
                     * Pada mobile/tablet:
                     * sidebar ditutup sebelum pindah halaman.
                     *
                     * Pada desktop:
                     * halaman langsung berpindah.
                     */

                    if (!isDesktop()) {

                        closeSidebar();

                    }

                }
            );

        }
    );


    /* =====================================================
       RESIZE
    ===================================================== */

    let previousDesktopState =
        isDesktop();


    window.addEventListener(
        'resize',
        function () {


            const currentDesktopState =
                isDesktop();


            /* -------------------------------------------
               Tidak ada perubahan mode
            ------------------------------------------- */

            if (
                currentDesktopState ===
                previousDesktopState
            ) {

                return;

            }


            previousDesktopState =
                currentDesktopState;


            /* -------------------------------------------
               MASUK DESKTOP
            ------------------------------------------- */

            if (currentDesktopState) {


                sidebar.classList.remove(
                    'show'
                );


                sidebar.classList.remove(
                    'desktop-closed'
                );


                if (sidebarOverlay) {

                    sidebarOverlay.classList.remove(
                        'show'
                    );

                }


                document.body.classList.remove(
                    'sidebar-open'
                );


                sidebar.setAttribute(
                    'aria-hidden',
                    'false'
                );


                if (hamburgerBtn) {

                    hamburgerBtn.classList.remove(
                        'hidden'
                    );

                    hamburgerBtn.classList.remove(
                        'desktop-visible'
                    );

                    hamburgerBtn.setAttribute(
                        'aria-expanded',
                        'true'
                    );

                }

            }


            /* -------------------------------------------
               MASUK MOBILE / TABLET
            ------------------------------------------- */

            else {


                sidebar.classList.remove(
                    'desktop-closed'
                );


                sidebar.classList.remove(
                    'show'
                );


                if (sidebarOverlay) {

                    sidebarOverlay.classList.remove(
                        'show'
                    );

                }


                document.body.classList.remove(
                    'sidebar-open'
                );


                sidebar.setAttribute(
                    'aria-hidden',
                    'true'
                );


                if (hamburgerBtn) {

                    hamburgerBtn.classList.remove(
                        'hidden'
                    );

                    hamburgerBtn.classList.remove(
                        'desktop-visible'
                    );

                    hamburgerBtn.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                }

            }

        }
    );


    /* =====================================================
       KONDISI AWAL
    ===================================================== */

    if (isDesktop()) {


        /* -----------------------------------------------
           Desktop:
           sidebar terbuka
        ----------------------------------------------- */

        sidebar.classList.remove(
            'desktop-closed'
        );

        sidebar.classList.remove(
            'show'
        );


        if (sidebarOverlay) {

            sidebarOverlay.classList.remove(
                'show'
            );

        }


        sidebar.setAttribute(
            'aria-hidden',
            'false'
        );


        if (hamburgerBtn) {

            hamburgerBtn.classList.remove(
                'hidden'
            );

            hamburgerBtn.classList.remove(
                'desktop-visible'
            );

            hamburgerBtn.setAttribute(
                'aria-expanded',
                'true'
            );

        }

    } else {


        /* -----------------------------------------------
           Mobile:
           sidebar tertutup
        ----------------------------------------------- */

        sidebar.classList.remove(
            'desktop-closed'
        );

        sidebar.classList.remove(
            'show'
        );


        if (sidebarOverlay) {

            sidebarOverlay.classList.remove(
                'show'
            );

        }


        sidebar.setAttribute(
            'aria-hidden',
            'true'
        );


        if (hamburgerBtn) {

            hamburgerBtn.classList.remove(
                'hidden'
            );

            hamburgerBtn.classList.remove(
                'desktop-visible'
            );

            hamburgerBtn.setAttribute(
                'aria-expanded',
                'false'
            );

        }

    }

});

</script>


{{-- =========================================================
    SCRIPT KHUSUS HALAMAN
========================================================= --}}

@stack('scripts')


</body>

</html>