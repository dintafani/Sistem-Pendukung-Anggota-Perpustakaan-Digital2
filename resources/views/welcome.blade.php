<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Perpustakaan Digital</title>

    {{-- Font dari desain Figma --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">

  {{-- CSS UTAMA --}}
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">


    {{-- POPUP LOGIN & REGISTER --}}
    <link rel="stylesheet" href="{{ asset('css/popup/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/popup/register.css') }}">

    {{-- Font dari Google --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>

<body>

    {{-- =========================================
         APP / WADAH UTAMA SELURUH HALAMAN
    ========================================== --}}
    <div class="app">

        {{-- =====================================
             NAVBAR
        ====================================== --}}
        <nav class="navbar">

            <div class="navbar-container">

                {{-- Logo + Nama --}}
                <div class="navbar-brand">

                    <div class="logo">
                        📚
                    </div>

                    <div class="brand-text">

                        <div class="brand-title">
                            Library
                        </div>

                        <div class="brand-school">
                            SMA Negeri 03 Kotabumi
                        </div>

                    </div>

                </div>


                {{-- Menu --}}
                <div class="navbar-menu">

              <a href="#beranda" class="nav-link">
    Beranda
</a>

<a href="#koleksi" class="nav-link">
    Koleksi
</a>

<a href="#bantuan" class="nav-link">
    Tentang
</a>

                </div>

{{-- =========================================
     TOMBOL LOGIN & REGISTER
========================================== --}}

<div class="navbar-buttons">

    {{-- MASUK --}}
    <a
        href="#"
        id="openLogin"
        class="btn-login"
    >
        Masuk
    </a>

    {{-- DAFTAR --}}
   <a
    href="#"
    id="openRegister"
    class="btn-register"
    >
    Daftar Sekarang ✨
    </a>
</div> 

</nav>


       {{-- =========================================
     RUNNING TEXT / ANNOUNCEMENT BAR
========================================== --}}
<div class="announcement-bar">

    <div class="announcement-track">

        {{-- Kelompok pertama --}}
        <div class="announcement-group">

            <span>📖 "Membaca adalah jendela dunia"</span>

            <span>✦</span>

            <span>🌟 "Buku adalah teman terbaik yang tak pernah mengecewakan"</span>

            <span>✦</span>

            <span>📚 "Satu buku, seribu petualangan"</span>

            <span>✦</span>

            <span>💡 "Literasi bukan sekadar membaca, tapi memahami dunia"</span>

            <span>✦</span>

            <span>🚀 "Dengan membaca, kita bisa menjelajahi alam semesta tanpa batas"</span>

            <span>✦</span>

            <span>✏️ "Kata-kata membangun peradaban"</span>

            <span>✦</span>

        </div>


        {{-- Kelompok kedua untuk membuat animasi berjalan tanpa putus --}}
        <div class="announcement-group">

            <span>📖 "Membaca adalah jendela dunia"</span>

            <span>✦</span>

            <span>🌟 "Buku adalah teman terbaik yang tak pernah mengecewakan"</span>

            <span>✦</span>

            <span>📚 "Satu buku, seribu petualangan"</span>

            <span>✦</span>

            <span>💡 "Literasi bukan sekadar membaca, tapi memahami dunia"</span>

            <span>✦</span>

            <span>🚀 "Dengan membaca, kita bisa menjelajahi alam semesta tanpa batas"</span>

            <span>✦</span>

            <span>✏️ "Kata-kata membangun peradaban"</span>

            <span>✦</span>

        </div>

    </div>

</div>


{{-- =========================================
     HERO / LANDING SECTION
========================================== --}}
<main class="welcome-page">

        {{-- =====================================================
             BACKGROUND DECORATION
        ====================================================== --}}

        <div class="glow glow-yellow"></div>
        <div class="glow glow-orange"></div>
        <div class="glow glow-blue"></div>


        {{-- =====================================================
             FLOATING EMOJI
        ====================================================== --}}

        <div class="floating-emoji emoji-star">
            ⭐
        </div>

        <div class="floating-emoji emoji-note">
            📝
        </div>

        <div class="floating-emoji emoji-light">
            💡
        </div>

        <div class="floating-emoji emoji-graduate">
            🎓
        </div>


        {{-- =====================================================
             HERO CONTAINER
        ====================================================== --}}

        <section class="hero-container" id="beranda">

            {{-- =================================================
                 LEFT CONTENT
            ================================================== --}}

            <div class="hero-content">

                {{-- Welcome Badge --}}
                <div class="welcome-badge">
                    <span class="wave">👋</span>

                    <span>
                        Selamat datang, pejuang literasi!
                    </span>
                </div>


                {{-- =================================================
                     TITLE
                ================================================== --}}

                <div class="hero-title">

                    <div class="title-dark">
                        Perpustakaan
                    </div>

                    <div class="title-orange">
                        Digital Seru
                    </div>

                    {{-- Garis dekorasi --}}
                    <div class="title-line"></div>

                    <div class="title-dark title-last">
                        untuk Kita! 🎉
                    </div>

                </div>


                {{-- =================================================
                     DESCRIPTION
                ================================================== --}}

                <p class="hero-description">
                    Temukan ribuan buku favoritmu, pinjam tanpa antri,
                    dan catat perjalanan membacamu semua dalam satu
                    platform yang bikin baca jadi makin asyik!
                </p>


                {{-- =================================================
                     LOGIN BUTTONS
                ================================================== --}}

                <div class="login-buttons">

                    {{-- SISWA --}}
                  <a href="#" id="openLoginStudent" class="login-btn student-btn">

    <span class="button-icon">
        🎒
    </span>

    <span>
        Masuk sebagai Siswa
    </span>

</a>


                    {{-- PETUGAS --}}
                    <a href="#" id="openLoginStaff" class="login-btn staff-btn">

    <span class="button-icon">
        📋
    </span>

    <span>
        Masuk sebagai Petugas
    </span>

</a>

{{-- DAFTAR HADIR --}}
<a href="{{ route('attendance.create') }}" class="login-btn attendance-btn">

    <span class="button-icon">
        📑
    </span>

    <span>
        Daftar Hadir
    </span>

</a>

                </div>


                {{-- =================================================
                     STATISTIK
                ================================================== --}}

                <div class="statistics">

                    <div class="stat-item">
                        <strong>{{ $totalBuku }}</strong>
                        <span>Koleksi Buku</span>
                    </div>

                    <div class="stat-item">
                        <strong>{{ $totalSiswa }}</strong>
                        <span>Siswa Aktif</span>
                    </div>

                    <div class="stat-item stat-large">
                        <strong>{{ $totalPeminjaman }}</strong>
                        <span>Peminjaman Bulan Ini</span>
                    </div>

                </div>

            </div>


            {{-- =================================================
                 RIGHT ILLUSTRATION
            ================================================== --}}

            <div class="hero-illustration">


                {{-- =============================================
                     RATING BADGE
                ============================================== --}}

                <div class="rating-badge">
                    <span>⭐</span>
                    <strong>4.9/5</strong>
                </div>


                {{-- =============================================
                     BOOK COUNT BADGE
                ============================================== --}}

                <div class="book-count-badge">
                    <span>📚</span>
                    <strong>{{ $totalBuku }}Buku</strong>
                </div>


                {{-- =============================================
                     BOOK STACK
                ============================================== --}}

                <div class="book-stack">


                    {{-- BOOK 1 --}}
                    <div class="book-card book-orange">

                        <div class="book-top-line"></div>

                        <div class="book-small-line"></div>

                        <div class="book-center">

                            <div class="book-icon">
                                📖
                            </div>

                            <div class="book-title">
                                Buku adalah jendela Dunia
                            </div>

                        </div>

                        <div class="book-author">
                            Pinjam Buku Sekarang!
                        </div>

                    </div>


                    {{-- BOOK 2 --}}
                    <div class="book-card book-purple">

                        <div class="book-top-line"></div>

                        <div class="book-small-line"></div>

                        <div class="book-center">

                            <div class="book-icon">
                                ✨
                            </div>

                            <div class="book-title">
                                Jembatan Ilmu
                            </div>

                        </div>

                        <div class="book-author">
                            Melihat Dunia Baru
                        </div>

                    </div>


                    {{-- BOOK 3 --}}
                    <div class="book-card book-teal">

                        <div class="book-top-line"></div>

                        <div class="book-small-line"></div>

                        <div class="book-center">

                            <div class="book-icon">
                                🚀
                            </div>

                            <div class="book-title">
                               Mencari
                            </div>

                        </div>

                        <div class="book-author">
                            Makna Tertentu
                        </div>

                    </div>

                </div>


                {{-- =============================================
                     MASKOT / CHARACTER
                ============================================== --}}

                <div class="mascot">

                    <div class="mascot-body">

                        <div class="mascot-face">

                            <span class="eye eye-left"></span>
                            <span class="eye eye-right"></span>

                            <span class="smile"></span>

                        </div>

                        <div class="mascot-book"></div>

                        <div class="mascot-arm"></div>

                        <div class="mascot-hat">
                            📚
                        </div>

                    </div>

                </div>


                {{-- =============================================
                     DECORATIVE GRADUATION
                ============================================== --}}

                <div class="illustration-graduate">
                    🎓
                </div>


                {{-- =============================================
                     LITTLE STARS
                ============================================== --}}

                <div class="little-star star-one">
                    ✦
                </div>

                <div class="little-star star-two">
                    ✨
                </div>

            </div>

        </section>

        {{-- =========================================================
     SECTION: KOLEKSI TERPILIH
========================================================= --}}

<section class="koleksi-section" id="koleksi">

    <div class="koleksi-container">

        {{-- HEADER --}}
        <div class="koleksi-header">

            <div class="koleksi-badge">
                <span class="badge-icon">✨</span>
                <span>Koleksi Terpilih</span>
            </div>

            <h2>
                Buku Pilihan Minggu Ini 📖
            </h2>

            <p>
                Dari novel seru sampai buku pengembangan diri 
                semua ada di sini!
            </p>

        </div>


       {{-- SEARCH + CATEGORY --}}

<div class="koleksi-filter">

    {{-- SEARCH --}}
    <div class="koleksi-search">

        <form action="{{ route('welcome') }}" method="GET">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari judul atau pengarang..."
                id="koleksiSearch"
            >

        </form>

    </div>

<div class="kategori-list">

    {{-- TOMBOL SEMUA --}}
    <a
        href="{{ route('welcome', request()->except('kategori')) }}"
        class="kategori-btn {{ !request('kategori') ? 'active' : '' }}"
    >
        Semua
    </a>

    @if(request()->filled('kategori'))

        {{-- JIKA SUDAH MEMILIH KATEGORI --}}
        {{-- HANYA TAMPILKAN KATEGORI YANG DIPILIH --}}
        <a
            href="{{ route('welcome', array_merge(
                request()->except('kategori'),
                ['kategori' => request('kategori')]
            )) }}"
            class="kategori-btn active"
        >
            {{ request('kategori') }}
        </a>

    @else

        {{-- JIKA BELUM MEMILIH KATEGORI --}}
        {{-- TAMPILKAN SEMUA KATEGORI --}}
        @php
            $welcomeCategories = $books
                ->pluck('kategori')
                ->filter()
                ->flatMap(function ($kategori) {
                    return explode(',', $kategori);
                })
                ->map(function ($kategori) {
                    return trim($kategori);
                })
                ->filter()
                ->unique(function ($kategori) {
                    return strtolower($kategori);
                })
                ->sort()
                ->values();
        @endphp

        @foreach($welcomeCategories as $kategori)
            <a
                href="{{ route('welcome', array_merge(
                    request()->except('kategori'),
                    ['kategori' => $kategori]
                )) }}"
                class="kategori-btn"
            >
                {{ $kategori }}
            </a>
        @endforeach

    @endif

</div>
</div>


       {{-- BOOK LIST --}}
<div class="buku-grid">

    @forelse($books as $book)

        <article class="buku-card">

            {{-- COVER BUKU --}}
            <div class="buku-cover">

                @if($book->cover)

                    <img
                        src="{{ asset('storage/' . $book->cover) }}"
                        alt="{{ $book->judul }}"
                    >

                @else

                    <img
                        src="{{ asset('images/no-book.png') }}"
                        alt="Tidak ada cover"
                    >

                @endif

            </div>

            {{-- INFORMASI BUKU --}}
            <div class="buku-content">

               <div class="buku-top">
    <div class="buku-kategori-list">
        @if($book->kategori)
            @foreach(explode(',', $book->kategori) as $kategori)
                @php
                    $kategori = trim($kategori);
                @endphp

                @if($kategori !== '')
                    <span class="buku-kategori">
                        {{ $kategori }}
                    </span>
                @endif
            @endforeach
        @else
            <span class="buku-kategori">
                Umum
            </span>
        @endif
    </div>

    <div class="buku-rating">
        ⭐
        {{ number_format($book->reviews_avg_rating ?? 0, 1) }}
    </div>
</div>
                {{-- JUDUL --}}
                <h3>
                    {{ $book->judul }}
                </h3>


                {{-- PENULIS --}}
                <p class="buku-penulis">

                    oleh {{ $book->penulis }}

                </p>


                {{-- DESKRIPSI --}}
                <p class="buku-deskripsi">

                    {{ $book->deskripsi ?? 'Belum ada deskripsi buku.' }}

                </p>


                {{-- FOOTER --}}
                <div class="buku-footer">

                    <span class="buku-ulasan">

                        Stok:
                        {{ $book->stok ?? 0 }}

                    </span>


                    <a
                        href="{{ route('welcome.book.show', $book) }}"
                        class="btn-pinjam"
                    >
                        Lihat Buku
                    </a>

                </div>

            </div>

        </article>

    @empty

        <div style="
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px;
        ">

            <h3>
                Belum ada buku pada koleksi perpustakaan.
            </h3>

            <p>
                Silakan coba pencarian atau kategori lainnya.
            </p>

        </div>

    @endforelse

</div>

        {{-- BUTTON LIHAT SEMUA --}}
<div class="koleksi-more">

   <a
    href="{{ route('welcome.koleksi') }}"
    class="btn-semua-koleksi"
>
    <span>Lihat Semua Koleksi</span>
    <span class="arrow">→</span>
</a>
</div>

</section>
{{-- =========================================
         CARA KERJA
    ========================================== --}}


<section class="cara-kerja-section" id="bantuan">

    <div class="cara-kerja-container">

        {{-- HEADER --}}
        <div class="cara-kerja-header">

            <h2>Gimana Cara Kerjanya? 🤔</h2>

            <p>
                Super mudah, cuma 3 langkah!
            </p>

        </div>


        {{-- 3 LANGKAH --}}
        <div class="cara-kerja-grid">

            {{-- LANGKAH 1 --}}
            <div class="cara-kerja-card">

                <div class="cara-kerja-icon-wrap icon-orange">
                    <div class="cara-kerja-icon">
                        📱
                    </div>
                </div>

                <div class="cara-kerja-number number-orange">
                    1
                </div>

                <div class="cara-kerja-title">
                    Daftar &amp; Login
                </div>

                <p class="cara-kerja-description">
                    Buat akun dengan NIS kamu dan langsung masuk ke dunia perpustakaan digital!
                </p>

            </div>


            {{-- LANGKAH 2 --}}
            <div class="cara-kerja-card">

                <div class="cara-kerja-icon-wrap icon-purple">
                    <div class="cara-kerja-icon">
                        🔍
                    </div>
                </div>

                <div class="cara-kerja-number number-purple">
                    2
                </div>

                <div class="cara-kerja-title">
                    Cari Buku
                </div>

                <p class="cara-kerja-description">
                    Temukan buku yang kamu mau pakai fitur pencarian pintar.
                    Filter berdasarkan genre, pengarang, atau topik!
                </p>

            </div>


            {{-- LANGKAH 3 --}}
            <div class="cara-kerja-card">

                <div class="cara-kerja-icon-wrap icon-teal">
                    <div class="cara-kerja-icon">
                        📚
                    </div>
                </div>

                <div class="cara-kerja-number number-teal">
                    3
                </div>

                <div class="cara-kerja-title">
                    Pinjam &amp; Nikmati
                </div>

                <p class="cara-kerja-description">
                    Konfirmasi peminjaman, ambil bukunya di perpustakaan,
                    dan happy reading! Notifikasi jatuh tempo otomatis.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     SECTION ROLE SISWA & PETUGAS
========================================================= -->
<section class="role-section">

    <div class="role-container">

        <!-- HEADER -->
        <div class="role-header">
            <h2>Tersedia untuk Dua Role 🎭</h2>
            <p>Fitur yang disesuaikan untuk kebutuhanmu</p>
        </div>

        <!-- CARD WRAPPER -->
        <div class="role-cards">

            <!-- ================= SISWA ================= -->
            <div class="role-card role-card-siswa">

                <div class="role-card-header">

                    <div class="role-icon role-icon-siswa">
                        🎒
                    </div>

                    <div class="role-title">
                        <h3>Untuk Siswa</h3>
                        <span>Akses lengkap koleksi perpustakaan</span>
                    </div>

                </div>

                <div class="role-features">

                    <div class="role-feature">
                        🔍 Cari dan temukan buku dengan mudah
                    </div>

                    <div class="role-feature">
                        📚 Pinjam buku secara online
                    </div>

                    <div class="role-feature">
                        ⏰ Notifikasi jatuh tempo otomatis
                    </div>

                    <div class="role-feature">
                        📊 Lacak riwayat peminjaman
                    </div>

                    <div class="role-feature">
                        ⭐ Tulis ulasan dan rating buku
                    </div>

                    <div class="role-feature">
                        🎯 Rekomendasi buku personal
                    </div>

                    <div class="role-feature">
                        🏆 Leaderboard pembaca terbanyak
                    </div>

                </div>

              

                <div class="role-decoration">
                    🎒
                </div>

            </div>


            <!-- ================= PETUGAS ================= -->
            <div class="role-card role-card-petugas">

                <div class="role-card-header">

                    <div class="role-icon role-icon-petugas">
                        📋
                    </div>

                    <div class="role-title">
                        <h3>Untuk Petugas</h3>
                        <span>Kontrol penuh operasional perpustakaan</span>
                    </div>

                </div>

                <div class="role-features">

                    <div class="role-feature">
                        📦 Manajemen koleksi buku lengkap
                    </div>

                    <div class="role-feature">
                        👥 Kelola data anggota perpustakaan
                    </div>

                    <div class="role-feature">
                        📊 Dashboard laporan & statistik
                    </div>

                    <div class="role-feature">
                        ✅ Konfirmasi peminjaman & pengembalian
                    </div>

                    <div class="role-feature">
                        🔔 Notifikasi buku terlambat
                    </div>

                    <div class="role-feature">
                        📈 Export laporan ke Excel/PDF
                    </div>

                    <div class="role-feature">
                        ⚙️ Pengaturan sistem perpustakaan
                    </div>

                </div>

               
                <div class="role-decoration">
                    📋
                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     CTA / AJAKAN MEMBACA
========================================================= --}}

<section class="cta-section">

    <div class="cta-container">

        <div class="cta-card">

            {{-- Dekorasi --}}
            <div class="cta-decoration decoration-book">
                📚
            </div>

            <div class="cta-decoration decoration-star">
                ⭐
            </div>

            <div class="cta-decoration decoration-graduate">
                🎓
            </div>

            <div class="cta-decoration decoration-sparkle">
                🌟
            </div>

            <div class="cta-decoration decoration-rocket">
                🚀
            </div>


            {{-- Konten --}}
            <div class="cta-content">

                <h2 class="cta-title">
                    Yuk, Mulai Membaca Hari Ini!
                </h2>

                <p class="cta-description">
                    Bergabung dengan semua siswa yang sudah menikmati
                    kemudahan perpustakaan digital SMA Negeri 03 Kotabumi!
                </p>


                {{-- Tombol --}}
                <div class="cta-buttons">

                  <a
    href="#"
    id="openRegisterCta"
    class="cta-button cta-button-primary"
>
    Daftar Gratis Sekarang! ✨
</a>

                    <a
    href="#bantuan"
    class="cta-button cta-button-secondary"
>
    Pelajari Lebih Lanjut
</a>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- =========================================================
     FOOTER PERPUSTAKAAN
========================================================= -->
<footer class="library-footer">

    <div class="footer-container">

        <div class="footer-content">

            <!-- ================= BRAND ================= -->
            <div class="footer-brand">

                <div class="footer-brand-header">

                    <div class="footer-logo">
                        📚
                    </div>

                    <div class="footer-brand-text">
                        <h3>Library</h3>
                        <span>SMA Nusantara Bangsa</span>
                    </div>

                </div>

                <p class="footer-description">
                    Sistem informasi perpustakaan sekolah yang modern,
                    cepat, dan menyenangkan untuk semua siswa dan petugas.
                </p>

            </div>


            <!-- ================= MENU ================= -->
            <div class="footer-column">

                <h4>Menu</h4>

                <div class="footer-links">

                <div class="footer-links">

    <a href="#beranda">
        Beranda
    </a>

    <a href="#koleksi">
        Koleksi Buku
    </a>

    <a href="#bantuan">
        Bantuan
    </a>

    <a href="#hubungi">
        Hubungi Kami
    </a>

</div>
                </div>

            </div>


            <!-- ================= OPERASIONAL ================= -->
            <div class="footer-column" id="hubungi">
    
    <h4>Jam Operasional</h4>

    <div class="footer-info">

        <div>
            📅 Senin – Jumat: 07.00 – 15.30
        </div>

        <div>
            📅 Sabtu: 08.00 – 12.00
        </div>

        <div>
            📍 JL. SERSAN LABA GOLE NO.45 KOTA ALAM
        </div>

        <div>
            📞 089520668555
        </div>

    </div>

</div>
</div>



        <!-- ================= COPYRIGHT ================= -->
        <div class="footer-bottom">

            <div class="footer-copyright">
                © 2026 Library · SMA Negeri 03 Kotabumi.
                All rights reserved.
            </div>

            <div class="footer-made">
                Made with ❤️ untuk para pembaca muda Indonesia
            </div>

        </div>

</footer>

@include('components.login-modal')
@include('components.register-modal')

<script src="{{ asset('js/script.js') }}"></script>
</body>

</html>