<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Siswa - Perpustakaan Digital</title>


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
        href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700;800;900&family=Nunito:wght@400;600;700;800;900&display=swap"
        rel="stylesheet"
    >


    {{-- =====================================================
         FONT AWESOME
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    {{-- =====================================================
         CSS DASHBOARD SISWA
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/dashboard/siswa.css') }}"
    >

</head>


<body>

@php
    $unreadCount = \App\Models\Notification::where(
        'user_id',
        Auth::id()
    )
    ->where('dibaca', false)
    ->count();
@endphp

<div class="dashboard-wrapper">


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    <aside class="sidebar">

        <div class="sidebar-logo">

            <div class="logo-icon">
                📚
            </div>

            <div class="logo-text">
                PD
            </div>

        </div>


        <div class="sidebar-line"></div>


        <nav class="sidebar-menu">

            <a
                href="{{ route('siswa.dashboard') }}"
                class="sidebar-item active"
                title="Dashboard"
            >
                <span class="sidebar-icon">🏠</span>
                <span class="sidebar-tooltip">Dashboard</span>
            </a>


            <a
                href="{{ route('siswa.books.index') }}"
                class="sidebar-item"
                title="Koleksi Buku"
            >
                <span class="sidebar-icon">🔍</span>
                <span class="sidebar-tooltip">Koleksi Buku</span>
            </a>


            <a
                href="{{ route('siswa.history') }}"
                class="sidebar-item"
                title="Histori Peminjaman"
            >
                <span class="sidebar-icon">📋</span>
                <span class="sidebar-tooltip">
                    Histori Peminjaman
                </span>
            </a>


            <a
                href="{{ route('siswa.leaderboard') }}"
                class="sidebar-item"
                title="Leaderboard"
            >
                <span class="sidebar-icon">🏆</span>
                <span class="sidebar-tooltip">Leaderboard</span>
            </a>

        </nav>


        <div class="sidebar-bottom">

            <div class="sidebar-line"></div>


         {{-- NOTIFIKASI --}} <a href="{{ route('siswa.notifikasi') }}" class="sidebar-item" > <span class="sidebar-icon"> 🔔 @if(isset($unreadCount) && $unreadCount > 0) <span class="notification-badge"> {{ $unreadCount > 99 ? '99+' : $unreadCount }} </span> @endif </span> </a>


            <a
                href="{{ route('siswa.account') }}"
                class="profile-button"
                title="Akun Saya"
            >

                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}

                <span class="online-dot"></span>

                <span class="sidebar-tooltip bottom-tooltip">
                    Akun Saya
                </span>

            </a>


            <form
                action="{{ route('logout') }}"
                method="POST"
                class="logout-form"
            >

                @csrf

                <button
                    type="submit"
                    class="logout-button"
                    title="Logout"
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
         MAIN CONTENT
    ====================================================== --}}

    <main class="main-content">

        <div class="dashboard-container">

            <section class="top-section">


                {{-- =================================================
                     LEFT COLUMN
                ================================================== --}}

                <div class="left-column">


                    {{-- =================================================
                         JAM + SAPAAN
                    ================================================== --}}

                    <div class="welcome-row">

                        <div class="clock-card">

                            <div class="clock-circle">

                                <div
                                    id="seconds"
                                    class="seconds-number"
                                >
                                    00
                                </div>

                                <div class="seconds-label">
                                    detik
                                </div>

                            </div>


                            <div class="clock-info">

                                <div class="digital-time">

                                    <span id="hours">
                                        00
                                    </span>

                                    <span class="clock-separator">
                                        :
                                    </span>

                                    <span id="minutes">
                                        00
                                    </span>

                                </div>


                                <div class="date-info">

                                    <span
                                        id="day"
                                        class="day-badge"
                                    >
                                        Hari
                                    </span>

                                    <span id="date">
                                        00 Agu 2026
                                    </span>

                                </div>

                            </div>

                        </div>


                        <div class="greeting-card">

                            <div
                                class="greeting-small"
                                id="greetingText"
                            >
                                ☀️ Selamat Pagi
                            </div>


                            <div class="greeting-title">

                                Halo,<br>

                                {{ Auth::user()->name }}! 👋

                            </div>


                            <div class="greeting-description">
                                Siap menemukan cerita baru hari ini?
                            </div>


                            <div class="greeting-circle circle-one"></div>

                            <div class="greeting-circle circle-two"></div>

                        </div>

                    </div>



                    {{-- =================================================
                         QUICK MENU
                    ================================================== --}}

                    <div class="stats-row quick-menu-row">


                        <a
                            href="{{ route('siswa.books.index') }}"
                            class="quick-card quick-book"
                        >

                            <img
                                src="{{ asset('images/jelajahi.jpeg') }}"
                                class="quick-image"
                                alt="Jelajahi Buku"
                            >

                            <div class="quick-overlay"></div>

                            <div class="quick-content">

                                <div class="quick-icon">
                                    🔍
                                </div>

                                <div class="quick-title">
                                    Jelajahi Buku
                                </div>

                                <div class="quick-description">

                                    {{ number_format($totalBuku ?? 0, 0, ',', '.') }}+
                                    koleksi

                                </div>

                            </div>

                        </a>


                        <a
                            href="{{ route('siswa.books.index') }}"
                            class="quick-card quick-read"
                        >

                            <img
                                src="{{ asset('images/bacabuku.jpeg') }}"
                                class="quick-image"
                                alt="Baca Sekarang"
                            >

                            <div class="quick-overlay"></div>

                            <div class="quick-content">

                                <div class="quick-icon">
                                    📖
                                </div>

                                <div class="quick-title">
                                    Baca Sekarang
                                </div>

                                <div class="quick-description">
                                    Lanjut membaca
                                </div>

                            </div>

                        </a>


                        <a
                            href="{{ route('siswa.history') }}"
                            class="quick-card quick-category"
                        >

                            <img
                                src="{{ asset('images/pinjam buku.jpeg') }}"
                                class="quick-image"
                                alt="Buku Dipinjam"
                            >

                            <div class="quick-overlay"></div>

                            <div class="quick-content">

                                <div class="quick-icon">
                                    📚
                                </div>

                                <div class="quick-title">
                                    Buku Dipinjam
                                </div>

                                <div class="quick-description">
                                    Lihat buku yang sedang dipinjam
                                </div>

                            </div>

                        </a>


                        <a
                            href="#gamifikasi"
                            class="quick-card quick-points"
                        >

                            <img
                                src="{{ asset('images/poin.jpeg') }}"
                                class="quick-image"
                                alt="Poin"
                            >

                            <div class="quick-overlay"></div>

                            <div class="quick-content">

                                <div class="quick-icon">
                                    🏆
                                </div>

                                <div class="quick-title">
                                    Poin Saya
                                </div>

                            </div>

                            <div class="quick-points-number">
                                {{ Auth::user()->poin ?? 0 }}
                            </div>

                        </a>

                    </div>



                    {{-- =================================================
                         Pengumuman
                    ================================================== --}}

                    <section
                        class="book-gallery-section"
                        id="koleksi"
                    >


                        {{-- HEADER --}}

                        <div class="book-gallery-header">

                            <div class="book-gallery-heading">

                                <div class="book-gallery-eyebrow">
                                    ✦ PAPAN PENGUMUMAN
                                </div>

                                <h2>
                                    What's
                                    <span>New</span>
                                    Today?
                                </h2>

                            </div>


                            <p>
                                Pengumuman Terbaru dari Perpustakaan
                            </p>

                        </div>


                        {{-- =================================================
                             DATA POSTER
                        ================================================== --}}

                        @php

                            $activePosters =
    ($posters ?? collect())
    ->values();


                            $galleryBooks =
                                $activePosters
                                ->map(function ($poster) {

                                    return $poster->book;

                                })
                                ->filter()
                                ->unique('id')
                                ->values();


                            $bookGroups =
                                $galleryBooks
                                ->chunk(4)
                                ->values();

                        @endphp


                        @if(
                            $galleryBooks->count() > 0 ||
                            $activePosters->count() > 0
                        )


                            <div class="book-showcase">


                                {{-- =================================================
                                     BUKU
                                ================================================== --}}

                                @if($galleryBooks->count() > 0)

                                    <div class="book-strip-wrapper">


                                        <button
                                            type="button"
                                            class="book-strip-arrow book-strip-prev"
                                            id="bookStripPrev"
                                            aria-label="Buku sebelumnya"
                                        >
                                            ‹
                                        </button>


                                        <div class="book-strip-viewport">

                                            <div
                                                class="book-strip-track"
                                                id="bookStripTrack"
                                            >
@foreach($bookGroups as $groupIndex => $group)

    <div
        class="book-group-slide"
        data-group="{{ $groupIndex }}"
    >

        @foreach($group as $book)

            <a
                href="{{ route('siswa.books.show', $book) }}"
                class="book-showcase-card"
            >

                @if($book->cover)

                    <img
                        src="{{ asset('storage/' . $book->cover) }}"
                        alt="{{ $book->judul }}"
                        loading="lazy"
                    >

                @else

                    <img
                        src="https://placehold.co/600x800?text=Cover+Buku"
                        alt="Cover buku"
                    >

                @endif

                <div class="book-showcase-overlay"></div>

                <div class="book-showcase-label">
                    📚 BUKU
                </div>

                <div class="book-showcase-info">

                    <h3 class="book-showcase-title">
                        {{ $book->judul }}
                    </h3>

                    @if($book->penulis)

                        <div class="book-showcase-author">
                            {{ $book->penulis }}
                        </div>

                    @endif

                </div>

            </a>

        @endforeach


        {{-- SLOT KOSONG --}}

        @for(
            $emptyIndex = $group->count();
            $emptyIndex < 4;
            $emptyIndex++
        )

            <div
                style="
                    visibility:hidden;
                    pointer-events:none;
                "
            ></div>

        @endfor

    </div>

@endforeach
                                            </div>

                                        </div>


                                        <button
                                            type="button"
                                            class="book-strip-arrow book-strip-next"
                                            id="bookStripNext"
                                            aria-label="Buku berikutnya"
                                        >
                                            ›
                                        </button>

                                    </div>

                                @endif


                                <div class="book-poster-gap"></div>


                                {{-- =================================================
                                     POSTER
                                     1 POSTER SEKALIGUS
                                ================================================== --}}

                              @if($activePosters->count() > 0)

    <div class="poster-showcase-wrapper">

        <button
            type="button"
            class="poster-showcase-arrow poster-showcase-prev"
            id="posterShowcasePrev"
            aria-label="Poster sebelumnya"
        >
            ‹
        </button>


        <div class="poster-showcase-viewport">

            <div
                class="poster-showcase-track"
                id="posterShowcaseTrack"
            >

                @foreach($activePosters as $poster)

                    <div class="poster-showcase-slide">

                        @if($poster->book)

                            <a
                                href="{{ route(
                                    'siswa.books.show',
                                    $poster->book
                                ) }}"
                                class="poster-showcase-card"
                            >

                        @else

                            <div class="poster-showcase-card">

                        @endif


                            @if($poster->gambar)

                                <img
                                    src="{{ asset(
                                        'storage/' . $poster->gambar
                                    ) }}"
                                    alt="{{ $poster->judul }}"
                                    loading="lazy"
                                >

                            @else

                                <img
                                    src="https://placehold.co/1200x500?text=Poster+Promosi"
                                    alt="Poster promosi"
                                >

                            @endif


                            <div
                                class="poster-showcase-overlay"
                            ></div>


                            <div
                                class="poster-showcase-label"
                            >
                                ✦ Sneak a Peek
                            </div>


                            <div
                                class="poster-showcase-info"
                            >

                                <h3
                                    class="poster-showcase-title"
                                >
                                    {{ $poster->judul }}
                                </h3>


                                @if($poster->deskripsi)

                                    <div
                                        class="poster-showcase-description"
                                    >
                                        {{ \Illuminate\Support\Str::limit(
                                            $poster->deskripsi,
                                            140
                                        ) }}
                                    </div>

                                @endif


                                {{-- BUKU TERKAIT --}}
                                @if($poster->book)

                                    <div
                                        class="poster-showcase-book"
                                    >
                                        📚
                                        {{ $poster->book->judul }}
                                    </div>

                                @endif

                            </div>


                        @if($poster->book)

                            </a>

                        @else

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        </div>


        <button
            type="button"
            class="poster-showcase-arrow poster-showcase-next"
            id="posterShowcaseNext"
            aria-label="Poster berikutnya"
        >
            ›
        </button>

    </div>


    {{-- DOT --}}

    <div
        class="book-promotion-dots"
        id="posterShowcaseDots"
    >

        @foreach(
            $activePosters
            as $index => $poster
        )

            <button
                type="button"
                class="book-promotion-dot {{ $index === 0 ? 'active' : '' }}"
                data-poster-slide="{{ $index }}"
                aria-label="Poster {{ $index + 1 }}"
            ></button>

        @endforeach

    </div>

@endif

                            </div>

                        @else


                            {{-- EMPTY --}}

                            <div class="book-promotion-empty">

                                <div class="book-promotion-empty-icon">
                                    🖼️
                                </div>

                                <strong>
                                    Belum ada Pengumuman
                                </strong>

                                <span>
                                    Pengumuman yang ditambahkan petugas
                                    dan dihubungkan dengan buku
                                    akan tampil di sini.
                                </span>

                            </div>

                        @endif

                    </section>


                </div>



                {{-- =================================================
                     RIGHT COLUMN
                ================================================== --}}

                <div class="right-column">


                    {{-- =================================================
                         PEMINJAMAN
                    ================================================== --}}

                    <section
                        class="borrowing-card"
                        id="peminjaman"
                    >

                        <div class="borrowing-header">

                            <h2>
                                📋 Peminjaman
                            </h2>

                            <a
                                href="{{ route('siswa.history') }}"
                            >
                                Lihat Semua
                            </a>

                        </div>


                        <div class="borrowing-list">

                            @forelse(
                                ($borrowings ?? collect())->take(1)
                                as $borrowing
                            )

                                <div class="borrow-item">


                                    <div class="book-cover">

                                        @if(
                                            $borrowing->book &&
                                            $borrowing->book->cover
                                        )

                                            <img
                                                src="{{ asset(
                                                    'storage/' .
                                                    $borrowing->book->cover
                                                ) }}"
                                                alt="{{ $borrowing->book->judul }}"
                                                loading="lazy"
                                            >

                                        @else

                                            <img
                                                src="https://placehold.co/52x72?text=Book"
                                                alt="Buku"
                                            >

                                        @endif

                                    </div>


                                    <div class="borrow-info">

                                        <div class="book-title">
                                            {{ $borrowing->book->judul ?? 'Buku' }}
                                        </div>

                                        <div class="book-author">
                                            {{ $borrowing->book->penulis ?? 'Penulis tidak diketahui' }}
                                        </div>

                                        <div class="book-date">

                                            ⏰

                                            @if($borrowing->tanggal_kembali)

                                                Kembali:

                                                {{
                                                    \Carbon\Carbon::parse(
                                                        $borrowing->tanggal_kembali
                                                    )->translatedFormat('d M Y')
                                                }}

                                            @else

                                                Sedang Dipinjam

                                            @endif

                                        </div>


                                        @php

                                            $progress = 70;

                                            if (
                                                $borrowing->tanggal_pinjam &&
                                                $borrowing->tanggal_kembali
                                            ) {

                                                $mulai =
                                                    \Carbon\Carbon::parse(
                                                        $borrowing->tanggal_pinjam
                                                    );

                                                $kembali =
                                                    \Carbon\Carbon::parse(
                                                        $borrowing->tanggal_kembali
                                                    );

                                                $hariTotal =
                                                    max(
                                                        1,
                                                        $mulai->diffInDays(
                                                            $kembali
                                                        )
                                                    );

                                                $hariBerjalan =
                                                    min(
                                                        $hariTotal,
                                                        max(
                                                            0,
                                                            $mulai->diffInDays(
                                                                now()
                                                            )
                                                        )
                                                    );

                                                $progress =
                                                    min(
                                                        100,
                                                        max(
                                                            5,
                                                            round(
                                                                (
                                                                    $hariBerjalan /
                                                                    $hariTotal
                                                                ) * 100
                                                            )
                                                        )
                                                    );

                                            }

                                        @endphp


                                        <div class="progress-info">

                                            <div class="progress-text">

                                                <span>
                                                    Progress
                                                </span>

                                                <strong
                                                    class="{{ $progress >= 90 ? 'danger' : '' }}"
                                                >
                                                    {{ $progress }}%
                                                </strong>

                                            </div>


                                            <div class="progress-track">

                                                <div
                                                    class="progress-value {{ $progress >= 90 ? 'progress-90' : 'progress-70' }}"
                                                    style="width: {{ $progress }}%;"
                                                ></div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="borrow-item empty-borrow">

                                    <div class="borrow-info">

                                        <div class="book-title">
                                            Belum ada buku dipinjam
                                        </div>

                                        <div class="book-author">
                                            Yuk mulai membaca buku.
                                        </div>

                                    </div>

                                </div>

                            @endforelse

                        </div>


                        {{-- PILIHAN HARI INI --}}

                        @if(
                            ($books ?? collect())->count() > 0
                        )

                            @php
                                $todayBook = $books->first();
                            @endphp


                            <div class="today-wrapper">

                                <a
                                    href="{{ route(
                                        'siswa.books.show',
                                        $todayBook
                                    ) }}"
                                    class="today-card"
                                >

                                    <div class="today-cover">

                                        @if($todayBook->cover)

                                            <img
                                                src="{{ asset(
                                                    'storage/' .
                                                    $todayBook->cover
                                                ) }}"
                                                alt="{{ $todayBook->judul }}"
                                                loading="lazy"
                                            >

                                        @else

                                            <img
                                                src="https://placehold.co/42x56?text=Book"
                                                alt="Buku"
                                            >

                                        @endif

                                    </div>


                                    <div class="today-info">

                                        <div class="today-label">
                                            PILIHAN HARI INI
                                        </div>

                                        <div class="today-title">
                                            {{ $todayBook->judul }}
                                        </div>

                                        <div class="today-author">
                                            {{ $todayBook->penulis }}
                                        </div>

                                    </div>


                                    <div class="today-arrow">
                                        →
                                    </div>

                                </a>

                            </div>

                        @endif

                    </section>



                    {{-- =================================================
                         GAMIFICATION
                    ================================================== --}}

                    <section
                        class="gamification-card"
                        id="gamifikasi"
                    >

                        <div class="game-glow"></div>


                        <div class="game-header">

                            <div class="game-eyebrow">
                                ✦ READ MORE. EARN MORE.
                            </div>

                            <h2>
                                Jadi Pembaca<br>
                                <span>Terbaik!</span>
                            </h2>

                            <p>
                                Baca buku, kumpulkan poin,
                                dan jadilah pembaca terbaik.
                            </p>

                        </div>


                      @php
    $poin = Auth::user()->poin ?? 0;

    // Badge dari Controller / User model
    $badge = $badge ?? Auth::user()->badge ?? 'Pemula';

    $level = match ($badge) {
        'Pemula' => 1,
        'Anggota Baru' => 2,
        'Pembaca Aktif' => 3,
        'Master Literasi' => 4,
        'Legend Literasi' => 5,
        default => 1,
    };

    $target = match ($level) {
        1 => 50,
        2 => 100,
        3 => 200,
        4 => 300,
        5 => 300,
        default => 50,
    };

    $progress = min(($poin / $target) * 100, 100);

    $nextPoint = max($target - $poin, 0);
@endphp
                        <div class="game-progress-card">

                            <div class="game-summary">

                                <div class="game-stat">

                                    <div class="game-number orange">
                                        {{ $poin }}
                                    </div>

                                    <div class="game-label">
                                        POIN
                                    </div>

                                </div>


                                <div class="game-divider"></div>


                                <div class="game-stat level-stat">

                                    <div class="game-number yellow">
                                        {{ $level }}
                                    </div>

                                    <div class="game-label">
                                        LEVEL
                                    </div>

                                </div>


                                <div class="game-level-info">

                                    <div class="game-level-title">
                                        {{ $badge }}
                                    </div>

                                    <div class="game-level-next">
                                        {{ $nextPoint }} poin lagi
                                    </div>

                                </div>

                            </div>


                            <div class="level-progress-wrapper">

                                <div class="level-progress-text">

                                    <span>
                                        Progress Badge Berikutnya
                                    </span>

                                    <strong>
                                        {{ round($progress) }}%
                                    </strong>

                                </div>


                                <div class="level-progress-track">

                                    <div
                                        class="level-progress-value"
                                        style="width: {{ $progress }}%;"
                                    ></div>

                                </div>

                            </div>

<div class="game-badges">

    <div class="game-badge badge-orange {{ $badge === 'Pemula' ? 'active' : '' }}">
        🌱
        <strong>
            Pemula
        </strong>
    </div>

    <div class="game-badge badge-yellow {{ $badge === 'Anggota Baru' ? 'active' : '' }}">
        📘
        <strong>
            Anggota Baru
        </strong>
    </div>

    <div class="game-badge badge-pink {{ $badge === 'Pembaca Aktif' ? 'active' : '' }}">
        📚
        <strong>
            Pembaca Aktif
        </strong>
    </div>

    <div class="game-badge badge-green {{ $badge === 'Master Literasi' ? 'active' : '' }}">
        🏆
        <strong>
            Master Literasi
        </strong>
    </div>
</div>




                        {{-- =================================================
                             LEADERBOARD
                        ================================================== --}}

                        <a
                            href="{{ route('siswa.leaderboard') }}"
                            class="leaderboard-section"
                            id="leaderboard"
                            style="
                                text-decoration:none;
                                color:inherit;
                            "
                        >

                            <div class="leaderboard-title">
                                🏆 Leaderboard
                            </div>


                            @php

                                $leaderboardData =
                                    collect(
                                        $leaderboard ?? []
                                    );

                                $topThree =
                                    $leaderboardData->take(3);

                            @endphp


                            <div class="podium">

                                @forelse(
                                    $topThree
                                    as $index => $siswa
                                )

                                    @php

                                        $initials =
                                            collect(
                                                preg_split(
                                                    '/\s+/',
                                                    trim($siswa->name)
                                                )
                                            )
                                            ->filter()
                                            ->map(
                                                fn($word) =>
                                                    strtoupper(
                                                        substr(
                                                            $word,
                                                            0,
                                                            1
                                                        )
                                                    )
                                            )
                                            ->take(2)
                                            ->implode('');

                                    @endphp


                                    <div
                                        class="podium-person {{
                                            $index == 0
                                                ? 'first'
                                                : (
                                                    $index == 1
                                                        ? 'second'
                                                        : 'third'
                                                )
                                        }}"
                                    >

                                        <div
                                            class="podium-avatar {{
                                                $index == 0
                                                    ? 'avatar-orange'
                                                    : (
                                                        $index == 1
                                                            ? 'avatar-yellow'
                                                            : 'avatar-pink'
                                                    )
                                            }}"
                                        >

                                            {{ $initials ?: '??' }}

                                        </div>


                                        <div class="podium-medal">

                                            @if($index == 0)
                                                🥇
                                            @elseif($index == 1)
                                                🥈
                                            @else
                                                🥉
                                            @endif

                                        </div>


                                        <div
                                            class="podium-block {{
                                                $index == 0
                                                    ? 'block-first'
                                                    : (
                                                        $index == 1
                                                            ? 'block-second'
                                                            : 'block-third'
                                                    )
                                            }}"
                                        >

                                            <strong>

                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $siswa->name,
                                                        10
                                                    )
                                                }}

                                            </strong>

                                            <span>
                                                ⭐
                                                {{ number_format($siswa->poin) }}
                                            </span>

                                        </div>

                                    </div>

                                @empty

                                    <div class="empty-leaderboard">
                                        Belum ada data leaderboard.
                                    </div>

                                @endforelse

                            </div>


                            <div class="leaderboard-list">

                                @forelse(
                                    $leaderboardData
                                    as $index => $siswa
                                )

                                    @php

                                        $initials =
                                            collect(
                                                preg_split(
                                                    '/\s+/',
                                                    trim($siswa->name)
                                                )
                                            )
                                            ->filter()
                                            ->map(
                                                fn($word) =>
                                                    strtoupper(
                                                        substr(
                                                            $word,
                                                            0,
                                                            1
                                                        )
                                                    )
                                            )
                                            ->take(2)
                                            ->implode('');

                                        $rankNumber =
                                            $index + 1;

                                    @endphp


                                    <div class="leaderboard-item">

                                        <div
                                            class="rank {{
                                                $rankNumber == 1
                                                    ? 'orange-rank'
                                                    : (
                                                        $rankNumber == 2
                                                            ? 'yellow-rank'
                                                            : 'pink-rank'
                                                    )
                                            }}"
                                        >

                                            #{{ $rankNumber }}

                                        </div>


                                        <div
                                            class="leader-avatar {{
                                                $rankNumber == 1
                                                    ? 'avatar-small-orange'
                                                    : (
                                                        $rankNumber == 2
                                                            ? 'avatar-small-yellow'
                                                            : 'avatar-small-pink'
                                                    )
                                            }}"
                                        >

                                            {{ $initials ?: '??' }}

                                        </div>


                                        <div class="leader-name">
                                            {{ $siswa->name }}
                                        </div>


                                        <div class="leader-points">

                                            <strong>
                                                {{ number_format($siswa->poin) }}
                                            </strong>

                                            <span>
                                                pts
                                            </span>

                                        </div>

                                    </div>

                                @empty

                                    <div class="leaderboard-item">
                                        Belum ada leaderboard.
                                    </div>

                                @endforelse


                                <div class="leaderboard-item current-user">

                                    <div class="rank orange-rank">
                                        #{{ $ranking ?? '-' }}
                                    </div>


                                    <div
                                        class="leader-avatar avatar-small-orange"
                                    >

                                        {{
                                            strtoupper(
                                                substr(
                                                    Auth::user()->name,
                                                    0,
                                                    2
                                                )
                                            )
                                        }}

                                    </div>


                                    <div class="leader-name current-name">

                                        Kamu

                                        <span class="you-label">
                                            KAMU
                                        </span>

                                    </div>


                                    <div class="leader-points">

                                        <strong>

                                            {{
                                                number_format(
                                                    Auth::user()->poin ?? 0
                                                )
                                            }}

                                        </strong>

                                        <span>
                                            pts
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </a>

                    </section>

                </div>

            </section>

        </div>

    </main>

</div>



{{-- =========================================================
     JAVASCRIPT
     JAM + SAPAAN + SLIDER PAMERAN
========================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /* =====================================================
           JAM DIGITAL
        ====================================================== */

        function updateClock() {

            const now = new Date();

            const hours =
                String(now.getHours())
                .padStart(2, '0');

            const minutes =
                String(now.getMinutes())
                .padStart(2, '0');

            const seconds =
                String(now.getSeconds())
                .padStart(2, '0');


            const days = [
                'Minggu',
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu'
            ];


            const months = [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Agu',
                'Sep',
                'Okt',
                'Nov',
                'Des'
            ];


            const hoursElement =
                document.getElementById('hours');

            const minutesElement =
                document.getElementById('minutes');

            const secondsElement =
                document.getElementById('seconds');

            const dayElement =
                document.getElementById('day');

            const dateElement =
                document.getElementById('date');


            if (hoursElement) {
                hoursElement.textContent = hours;
            }

            if (minutesElement) {
                minutesElement.textContent = minutes;
            }

            if (secondsElement) {
                secondsElement.textContent = seconds;
            }

            if (dayElement) {
                dayElement.textContent =
                    days[now.getDay()];
            }

            if (dateElement) {

                dateElement.textContent =
                    `${now.getDate()} ${
                        months[now.getMonth()]
                    } ${now.getFullYear()}`;

            }

        }


        updateClock();

        setInterval(
            updateClock,
            1000
        );



        /* =====================================================
           SAPAAN OTOMATIS
        ====================================================== */

        function updateGreeting() {

            const hour =
                new Date().getHours();

            const greetingSmall =
                document.getElementById(
                    'greetingText'
                );


            if (!greetingSmall) {
                return;
            }


            if (
                hour >= 5 &&
                hour < 11
            ) {

                greetingSmall.textContent =
                    '☀️ Selamat Pagi';

            }

            else if (
                hour >= 11 &&
                hour < 15
            ) {

                greetingSmall.textContent =
                    '🌤️ Selamat Siang';

            }

            else if (
                hour >= 15 &&
                hour < 18
            ) {

                greetingSmall.textContent =
                    '🌅 Selamat Sore';

            }

            else {

                greetingSmall.textContent =
                    '🌙 Selamat Malam';

            }

        }


        updateGreeting();

        setInterval(
            updateGreeting,
            60000
        );



        /* =====================================================
           SLIDER PAMERAN DIGITAL
        ====================================================== */

        const bookTrack =
            document.getElementById(
                'bookStripTrack'
            );

        const bookPrev =
            document.getElementById(
                'bookStripPrev'
            );

        const bookNext =
            document.getElementById(
                'bookStripNext'
            );


        const posterTrack =
            document.getElementById(
                'posterShowcaseTrack'
            );

        const posterPrev =
            document.getElementById(
                'posterShowcasePrev'
            );

        const posterNext =
            document.getElementById(
                'posterShowcaseNext'
            );


        const posterDots =
            document.querySelectorAll(
                '[data-poster-slide]'
            );


        const showcase =
            document.querySelector(
                '.book-showcase'
            );


        /* =====================================================
           SLIDE COLLECTION
        ====================================================== */

        const bookSlides =
            bookTrack
                ? bookTrack.querySelectorAll(
                    '.book-group-slide'
                )
                : [];


        const posterSlides =
            posterTrack
                ? posterTrack.querySelectorAll(
                    '.poster-showcase-slide'
                )
                : [];


        let currentBookSlide = 0;

        let currentPosterSlide = 0;

        let autoSlideTimer = null;



        /* =====================================================
           UPDATE BUKU
        ====================================================== */

        function updateBooks(index) {

            if (
                !bookTrack ||
                bookSlides.length === 0
            ) {
                return;
            }


            if (index < 0) {

                index =
                    bookSlides.length - 1;

            }


            if (
                index >=
                bookSlides.length
            ) {

                index = 0;

            }


            currentBookSlide =
                index;


            bookTrack.style.transform =
                `translateX(-${
                    currentBookSlide * 100
                }%)`;

        }



        /* =====================================================
           UPDATE POSTER
        ====================================================== */

        function updatePoster(index) {

            if (
                !posterTrack ||
                posterSlides.length === 0
            ) {
                return;
            }


            if (index < 0) {

                index =
                    posterSlides.length - 1;

            }


            if (
                index >=
                posterSlides.length
            ) {

                index = 0;

            }


            currentPosterSlide =
                index;


            posterTrack.style.transform =
                `translateX(-${
                    currentPosterSlide * 100
                }%)`;


            posterDots.forEach(
                function (dot, dotIndex) {

                    dot.classList.toggle(
                        'active',
                        dotIndex ===
                        currentPosterSlide
                    );

                }
            );

        }



        /* =====================================================
           NEXT
        ====================================================== */

        function goNext() {

            if (bookSlides.length > 0) {

                updateBooks(
                    currentBookSlide + 1
                );

            }


            if (posterSlides.length > 0) {

                updatePoster(
                    currentPosterSlide + 1
                );

            }

        }



        /* =====================================================
           PREVIOUS
        ====================================================== */

        function goPrevious() {

            if (bookSlides.length > 0) {

                updateBooks(
                    currentBookSlide - 1
                );

            }


            if (posterSlides.length > 0) {

                updatePoster(
                    currentPosterSlide - 1
                );

            }

        }



        /* =====================================================
           BUKU NEXT
        ====================================================== */

        if (bookNext) {

            bookNext.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    goNext();

                    restartAutoSlide();

                }
            );

        }



        /* =====================================================
           BUKU PREV
        ====================================================== */

        if (bookPrev) {

            bookPrev.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    goPrevious();

                    restartAutoSlide();

                }
            );

        }



        /* =====================================================
           POSTER NEXT
        ====================================================== */

        if (posterNext) {

            posterNext.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    goNext();

                    restartAutoSlide();

                }
            );

        }



        /* =====================================================
           POSTER PREV
        ====================================================== */

        if (posterPrev) {

            posterPrev.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    goPrevious();

                    restartAutoSlide();

                }
            );

        }



        /* =====================================================
           DOT POSTER
        ====================================================== */

        posterDots.forEach(
            function (dot) {

                dot.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();

                        const index =
                            parseInt(
                                this.dataset.posterSlide,
                                10
                            );


                        if (!isNaN(index)) {

                            updatePoster(index);

                        }


                        restartAutoSlide();

                    }
                );

            }
        );



        /* =====================================================
           AUTO SLIDE
           SETIAP 5 DETIK
        ====================================================== */

        function startAutoSlide() {

            clearInterval(
                autoSlideTimer
            );


            if (
                bookSlides.length <= 1 &&
                posterSlides.length <= 1
            ) {

                return;

            }


            autoSlideTimer =
                setInterval(
                    function () {

                        goNext();

                    },
                    5000
                );

        }



        /* =====================================================
           RESTART
        ====================================================== */

        function restartAutoSlide() {

            startAutoSlide();

        }



        /* =====================================================
           PAUSE KETIKA MOUSE DI AREA PAMERAN
        ====================================================== */

        if (showcase) {

            showcase.addEventListener(
                'mouseenter',
                function () {

                    clearInterval(
                        autoSlideTimer
                    );

                }
            );


            showcase.addEventListener(
                'mouseleave',
                function () {

                    startAutoSlide();

                }
            );

        }



        /* =====================================================
           INITIAL
        ====================================================== */

        updateBooks(0);

        updatePoster(0);

        startAutoSlide();

    }

);

</script>


</body>

</html>