@extends('layouts.siswa')

@section('title','Leaderboard')

@section('styles')

<link rel="stylesheet" href="{{ asset('css/siswa/leaderboard.css') }}">

@endsection

@section('content')

<div class="leaderboard-page">


    <!-- ==========================================================
         HERO
    =========================================================== -->

    <div class="leaderboard-hero">

        <div class="hero-left">

            <span class="hero-label">
                🏆 Sistem Gamifikasi Literasi
            </span>

            <h1>
                Leaderboard Literasi
            </h1>

            <p>
                Tingkatkan budaya membaca dengan mengumpulkan poin literasi.
                Semakin banyak buku yang kamu baca dan selesaikan,
                semakin tinggi peringkatmu di leaderboard.
            </p>

            <div class="hero-tips">

                <div class="tip-item">
                    📖
                    <span>Baca Buku</span>
                </div>

                <div class="tip-item">
                    ⭐
                    <span>Dapatkan Poin</span>
                </div>

                <div class="tip-item">
                    🏅
                    <span>Naik Badge</span>
                </div>

                <div class="tip-item">
                    🎁
                    <span>Tukar Reward</span>
                </div>

            </div>

        </div>


        <div class="hero-right">

            <div class="hero-circle">
                🏆
            </div>

        </div>

    </div>



    <!-- ==========================================================
         DATA RANKING SAYA
    =========================================================== -->

    @php

        /*
        |--------------------------------------------------------------------------
        | Cari posisi siswa yang sedang login
        |--------------------------------------------------------------------------
        */

        $myRank = $leaderboard->search(function ($item) {

            return $item->id == Auth::id();

        });


        /*
        |--------------------------------------------------------------------------
        | Ranking sebenarnya
        |--------------------------------------------------------------------------
        */

        $myRankNumber = $myRank !== false
            ? $myRank + 1
            : null;


        /*
        |--------------------------------------------------------------------------
        | Ambil data siswa yang sedang login
        |--------------------------------------------------------------------------
        */

        $myUser = $myRank !== false
            ? $leaderboard->get($myRank)
            : Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Cek apakah siswa masuk Top 5
        |--------------------------------------------------------------------------
        */

        $isTopFive = $myRank !== false && $myRank < 5;


    @endphp



    <!-- ==========================================================
         STATISTIK
    =========================================================== -->

    <div class="leader-stats">


        <!-- TOTAL PESERTA -->

        <div class="leader-stat">

            <div class="stat-icon">
                👥
            </div>

            <h2>
                {{ $leaderboard->count() }}
            </h2>

            <span>
                Total Peserta
            </span>

        </div>



        <!-- POIN SAYA -->

        <div class="leader-stat">

            <div class="stat-icon">
                ⭐
            </div>

            <h2>
                {{ Auth::user()->poin }}
            </h2>

            <span>
                Poin Saya
            </span>

        </div>



        <!-- BADGE -->

        <div class="leader-stat">

            <div class="stat-icon">
                🏅
            </div>

            <h2>
                {{ Auth::user()->badge }}
            </h2>

            <span>
                Badge Saat Ini
            </span>

        </div>



        <!-- RANKING SAYA -->

        <div class="leader-stat">

            <div class="stat-icon">
                🏆
            </div>

            <h2>

                {{ $myRankNumber ? '#'.$myRankNumber : '-' }}

            </h2>

            <span>
                Ranking Saya
            </span>

        </div>

    </div>



    <!-- ==========================================================
         MOTIVASI
    =========================================================== -->

    <div class="motivation-card">

        <div class="motivation-icon">
            🚀
        </div>

        <div class="motivation-content">

            <h3>
                Terus Tingkatkan Literasimu!
            </h3>

            <p>
                Setiap buku yang kamu baca akan menambah pengalaman,
                wawasan, serta poin literasi.
                Naikkan badge-mu, capai posisi nomor satu,
                dan jadilah inspirasi bagi teman-temanmu.
            </p>

        </div>

    </div>



    <!-- ==========================================================
         PROGRESS BADGE
    =========================================================== -->

    @php

        $poin = Auth::user()->poin;

        if ($poin < 50) {

            $nextBadge = "Anggota Baru";
            $target = 50;

        } elseif ($poin < 100) {

            $nextBadge = "Pembaca Aktif";
            $target = 100;

        } elseif ($poin < 200) {

            $nextBadge = "Master Literasi";
            $target = 200;

        } else {

            $nextBadge = "Legend Literasi";
            $target = 300;

        }

        $progress = $target > 0
            ? min(($poin / $target) * 100, 100)
            : 0;

    @endphp



    <div class="progress-card">

        <div class="progress-header">

            <div>

                <h3>
                    Progress Badge Berikutnya
                </h3>

                <small>

                    Badge Selanjutnya :
                    <strong>
                        {{ $nextBadge }}
                    </strong>

                </small>

            </div>


            <div class="progress-point">

                {{ $poin }}

                <span>
                    / {{ $target }} Poin
                </span>

            </div>

        </div>



        <div class="progress-bar">

            <div
                class="progress-fill"
                style="width:{{ $progress }}%;"
            ></div>

        </div>



        <div class="progress-footer">

            🎯

            Kamu membutuhkan

            <strong>
                {{ max($target - $poin, 0) }}
            </strong>

            poin lagi untuk memperoleh badge

            <strong>
                {{ $nextBadge }}
            </strong>

        </div>

    </div>



    <!-- ==========================================================
         DATA PODIUM
    =========================================================== -->

    @php

        $juara1 = $leaderboard->get(0);
        $juara2 = $leaderboard->get(1);
        $juara3 = $leaderboard->get(2);

    @endphp



    <!-- ==========================================================
         PODIUM TOP 3
    =========================================================== -->

    @if($leaderboard->count() > 0)

    <div class="podium-section">

        <div class="section-title">

            <h2>
                🏆 Podium Juara Literasi
            </h2>

            <p>
                Selamat kepada siswa dengan perolehan poin tertinggi.
                Teruslah membaca agar namamu tampil di podium berikutnya.
            </p>

        </div>



        <div class="podium">


            <!-- ==================================================
                 JUARA 2
            =================================================== -->

            @if($juara2)

            <div class="podium-card second">

                <div class="medal">
                    🥈
                </div>

                <div class="avatar">

                    {{ strtoupper(substr($juara2->name, 0, 1)) }}

                </div>

                <h3>
                    {{ $juara2->name }}
                </h3>

                <span>
                    {{ $juara2->kelas }}
                </span>

                <div class="podium-points">

                    ⭐ {{ number_format($juara2->poin) }}

                </div>

                <div class="podium-badge">

                    {{ $juara2->badge }}

                </div>

            </div>

            @endif



            <!-- ==================================================
                 JUARA 1
            =================================================== -->

            @if($juara1)

            <div class="podium-card first">

                <div class="crown">
                    👑
                </div>

                <div class="medal">
                    🥇
                </div>

                <div class="avatar">

                    {{ strtoupper(substr($juara1->name, 0, 1)) }}

                </div>

                <h2>
                    {{ $juara1->name }}
                </h2>

                <span>
                    {{ $juara1->kelas }}
                </span>

                <div class="podium-points">

                    ⭐ {{ number_format($juara1->poin) }}

                </div>

                <div class="podium-badge">

                    {{ $juara1->badge }}

                </div>

                <div class="winner-text">

                    Juara Literasi

                </div>

            </div>

            @endif



            <!-- ==================================================
                 JUARA 3
            =================================================== -->

            @if($juara3)

            <div class="podium-card third">

                <div class="medal">
                    🥉
                </div>

                <div class="avatar">

                    {{ strtoupper(substr($juara3->name, 0, 1)) }}

                </div>

                <h3>
                    {{ $juara3->name }}
                </h3>

                <span>
                    {{ $juara3->kelas }}
                </span>

                <div class="podium-points">

                    ⭐ {{ number_format($juara3->poin) }}

                </div>

                <div class="podium-badge">

                    {{ $juara3->badge }}

                </div>

            </div>

            @endif

        </div>

    </div>

    @endif



    <!-- ==========================================================
         INFORMASI BADGE
    =========================================================== -->

    <div class="badge-guide">

        <div class="section-title">

            <h2>
                🎖️ Tingkatan Badge Literasi
            </h2>

            <p>
                Semakin banyak poin yang dikumpulkan,
                semakin tinggi level badge yang diperoleh.
            </p>

        </div>



        <div class="badge-grid">


            <div class="badge-item beginner">

                <div class="badge-icon">
                    🌱
                </div>

                <h4>
                    Pemula
                </h4>

                <small>
                    0 - 49 Poin
                </small>

            </div>



            <div class="badge-item new">

                <div class="badge-icon">
                    📘
                </div>

                <h4>
                    Anggota Baru
                </h4>

                <small>
                    50 - 99 Poin
                </small>

            </div>



            <div class="badge-item active">

                <div class="badge-icon">
                    📚
                </div>

                <h4>
                    Pembaca Aktif
                </h4>

                <small>
                    100 - 199 Poin
                </small>

            </div>



            <div class="badge-item master">

                <div class="badge-icon">
                    👑
                </div>

                <h4>
                    Master Literasi
                </h4>

                <small>
                    200 - 299 Poin
                </small>

            </div>



            <div class="badge-item legend">

                <div class="badge-icon">
                    🏆
                </div>

                <h4>
                    Legend Literasi
                </h4>

                <small>
                    300+ Poin
                </small>

            </div>

        </div>

    </div>



    <!-- ==========================================================
         LEADERBOARD TOP 5
    =========================================================== -->

    <div class="leaderboard-table-card">


        <div class="table-header">

            <div>

                <h2>
                    📊 Top 5 Peringkat Literasi
                </h2>

                <p>
                    Lima siswa dengan perolehan poin literasi tertinggi.
                </p>

            </div>

        </div>



        <div class="leaderboard-table-wrapper">

            <table class="leaderboard-table">

                <thead>

                    <tr>

                        <th width="90">
                            Rank
                        </th>

                        <th>
                            Siswa
                        </th>

                        <th>
                            NIS
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            Badge
                        </th>

                        <th>
                            Total Poin
                        </th>

                    </tr>

                </thead>



                <tbody>


                <!-- ==================================================
                     TOP 5
                =================================================== -->

                @forelse($leaderboard->take(5) as $index => $user)


                <tr class="{{ $user->id == Auth::id() ? 'my-rank' : '' }}">


                    <!-- RANK -->

                    <td>

                        @if($index == 0)

                            <span class="rank gold">
                                🥇
                            </span>

                        @elseif($index == 1)

                            <span class="rank silver">
                                🥈
                            </span>

                        @elseif($index == 2)

                            <span class="rank bronze">
                                🥉
                            </span>

                        @else

                            <span class="rank-number">
                                #{{ $index + 1 }}
                            </span>

                        @endif

                    </td>



                    <!-- SISWA -->

                    <td>

                        <div class="student-info">

                            <div class="student-avatar">

                                {{ strtoupper(substr($user->name, 0, 1)) }}

                            </div>


                            <div>

                                <h4>

                                    {{ $user->name }}


                                    @if($user->id == Auth::id())

                                        <span class="you-badge">
                                            Kamu
                                        </span>

                                    @endif

                                </h4>


                                <small>
                                    Anggota Perpustakaan
                                </small>

                            </div>

                        </div>

                    </td>



                    <!-- NIS -->

                    <td>

                        {{ $user->nis }}

                    </td>



                    <!-- KELAS -->

                    <td>

                        {{ $user->kelas }}

                    </td>



                    <!-- BADGE -->

                    <td>

                        @php

                            if($user->poin >= 300){

                                $badge = "Legend Literasi";
                                $class = "legend";
                                $icon = "🏆";

                            }

                            elseif($user->poin >= 200){

                                $badge = "Master Literasi";
                                $class = "master";
                                $icon = "👑";

                            }

                            elseif($user->poin >= 100){

                                $badge = "Pembaca Aktif";
                                $class = "active";
                                $icon = "📚";

                            }

                            elseif($user->poin >= 50){

                                $badge = "Anggota Baru";
                                $class = "new";
                                $icon = "📘";

                            }

                            else{

                                $badge = "Pemula";
                                $class = "beginner";
                                $icon = "🌱";

                            }

                        @endphp


                        <span class="badge {{ $class }}">

                            {{ $icon }}

                            {{ $badge }}

                        </span>

                    </td>



                    <!-- TOTAL POIN -->

                    <td>

                        <div class="point-box">

                            ⭐

                            <strong>
                                {{ number_format($user->poin) }}
                            </strong>

                        </div>

                    </td>

                </tr>


                @empty


                <tr>

                    <td colspan="6">

                        <div class="empty-state">

                            <div class="empty-icon">
                                📚
                            </div>

                            <h3>
                                Belum Ada Data Leaderboard
                            </h3>

                            <p>
                                Mulailah membaca buku untuk menjadi
                                siswa pertama yang memperoleh poin literasi.
                            </p>

                        </div>

                    </td>

                </tr>


                @endforelse

                </tbody>

            </table>

        </div>

    </div>



    <!-- ==========================================================
         PERINGKAT SAYA
         HANYA MUNCUL JIKA TIDAK MASUK TOP 5
    =========================================================== -->

    @if($myRank !== false && !$isTopFive)

    <div class="leaderboard-table-card my-ranking-card">

        <div class="table-header">

            <div>

                <h2>
                    👤 Peringkat Saya
                </h2>

                <p>
                    Kamu belum masuk dalam 5 besar.
                    Terus tingkatkan poin literasimu!
                </p>

            </div>

        </div>



        <div class="leaderboard-table-wrapper">

            <table class="leaderboard-table">

                <thead>

                    <tr>

                        <th width="90">
                            Rank
                        </th>

                        <th>
                            Siswa
                        </th>

                        <th>
                            NIS
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            Badge
                        </th>

                        <th>
                            Total Poin
                        </th>

                    </tr>

                </thead>



                <tbody>

                    <tr class="my-rank">


                        <!-- RANK -->

                        <td>

                            <span class="rank-number">

                                #{{ $myRankNumber }}

                            </span>

                        </td>



                        <!-- SISWA -->

                        <td>

                            <div class="student-info">

                                <div class="student-avatar">

                                    {{ strtoupper(substr($myUser->name, 0, 1)) }}

                                </div>


                                <div>

                                    <h4>

                                        {{ $myUser->name }}

                                        <span class="you-badge">
                                            Kamu
                                        </span>

                                    </h4>

                                    <small>
                                        Anggota Perpustakaan
                                    </small>

                                </div>

                            </div>

                        </td>



                        <!-- NIS -->

                        <td>

                            {{ $myUser->nis }}

                        </td>



                        <!-- KELAS -->

                        <td>

                            {{ $myUser->kelas }}

                        </td>



                        <!-- BADGE -->

                        <td>

                            @php

                                if($myUser->poin >= 300){

                                    $myBadge = "Legend Literasi";
                                    $myClass = "legend";
                                    $myIcon = "🏆";

                                }

                                elseif($myUser->poin >= 200){

                                    $myBadge = "Master Literasi";
                                    $myClass = "master";
                                    $myIcon = "👑";

                                }

                                elseif($myUser->poin >= 100){

                                    $myBadge = "Pembaca Aktif";
                                    $myClass = "active";
                                    $myIcon = "📚";

                                }

                                elseif($myUser->poin >= 50){

                                    $myBadge = "Anggota Baru";
                                    $myClass = "new";
                                    $myIcon = "📘";

                                }

                                else{

                                    $myBadge = "Pemula";
                                    $myClass = "beginner";
                                    $myIcon = "🌱";

                                }

                            @endphp


                            <span class="badge {{ $myClass }}">

                                {{ $myIcon }}

                                {{ $myBadge }}

                            </span>

                        </td>



                        <!-- POIN -->

                        <td>

                            <div class="point-box">

                                ⭐

                                <strong>

                                    {{ number_format($myUser->poin) }}

                                </strong>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        <!-- PESAN -->

        <div class="my-rank-message">

            🎯

            Kamu berada di peringkat

            <strong>
                #{{ $myRankNumber }}
            </strong>

            dengan

            <strong>
                {{ number_format($myUser->poin) }} poin
            </strong>.

            Terus membaca untuk masuk ke Top 5!

        </div>

    </div>

    @endif



    <!-- ==========================================================
         MOTIVASI FOOTER
    =========================================================== -->

    <div class="leaderboard-footer">

        <div class="footer-card">

            <div class="footer-icon">
                🚀
            </div>

            <div>

                <h3>
                    Terus Tingkatkan Prestasimu
                </h3>

                <p>
                    Membaca satu buku hari ini dapat membawamu
                    selangkah lebih dekat menuju posisi Juara Literasi.
                    Jangan berhenti belajar, kumpulkan poin,
                    raih badge tertinggi,
                    dan jadilah inspirasi bagi teman-temanmu.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection