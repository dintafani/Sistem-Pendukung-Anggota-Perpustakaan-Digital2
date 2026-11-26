
@extends('layouts.petugas')

@section('title', 'Dashboard')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard/petugas.css') }}">
@endsection

@section('content')


<!-- =====================================================
     WELCOME
===================================================== -->

<div class="welcome-section">

    <div class="welcome-text">

        <h1>
            👋
            @php
                $jam = now()->format('H');

                if ($jam >= 04 && $jam < 11) {
                    $salam = 'Selamat Pagi';
                } elseif ($jam >= 11 && $jam < 15) {
                    $salam = 'Selamat Siang';
                } elseif ($jam >= 15 && $jam < 18) {
                    $salam = 'Selamat Sore';
                } else {
                    $salam = 'Selamat Malam';
                }
            @endphp

            {{ $salam }}, {{ Auth::user()->name ?? 'Petugas' }}!
        </h1>

        <p>
            {{ now()->locale('id')->translatedFormat('l, d F Y') }}
        </p>

    </div>

    <div class="digital-clock">

        <span class="clock-dot"></span>

        <span id="digitalClock">-- : -- : --</span>

    </div>

</div>

<!-- =====================================================
     STATISTIK
===================================================== -->

<div class="card-grid">

    <!-- =================================================
         TOTAL ANGGOTA
    ================================================== -->

    <a href="{{ route('petugas.verifikasi') }}" class="stat-card-link">

        <div class="stat-card stat-orange">

            <div class="stat-card-top">

                <div class="stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>

                <span class="stat-badge">
                    +{{ number_format($anggotaHariIni ?? 0) }} hari ini
                </span>

            </div>

            <div class="stat-content">

                <h2>
                    {{ number_format($totalAnggota ?? 0) }}
                </h2>

                <span>
                    Total Anggota
                </span>

            </div>

            <div class="stat-bottom">

                <div class="mini-chart orange-chart">

                    @forelse($grafikAnggota ?? [] as $hari)

                        @php
                            $jumlah = $hari['jumlah'] ?? 0;

                            $height = ($maksAnggota ?? 0) > 0
                                ? ($jumlah / $maksAnggota) * 100
                                : 0;

                            $height = $jumlah > 0
                                ? max(8, $height)
                                : 4;
                        @endphp

                        <span
                            style="height: {{ $height }}%;"
                            title="{{ $hari['hari'] ?? '-' }}: {{ $jumlah }} anggota">
                        </span>

                    @empty

                        @for($i = 0; $i < 7; $i++)
                            <span style="height: 4%;"></span>
                        @endfor

                    @endforelse

                </div>

                <small>
                    7 hari
                </small>

            </div>

        </div>

    </a>


    <!-- =================================================
         TOTAL BUKU
    ================================================== -->

    <a href="{{ route('petugas.books.index') }}" class="stat-card-link">

        <div class="stat-card stat-purple">

            <div class="stat-card-top">

                <div class="stat-icon">
                    <i class="fa-solid fa-book"></i>
                </div>

                <span class="stat-badge">
                    {{ number_format($bukuAktif ?? 0) }} tersedia
                </span>

            </div>

            <div class="stat-content">

                <h2>
                    {{ number_format($totalBuku ?? 0) }}
                </h2>

                <span>
                    Total Buku
                </span>

            </div>

            <div class="stat-bottom">

                <div class="mini-chart purple-chart">

                    @forelse($grafikBuku ?? [] as $hari)

                        @php
                            $jumlah = $hari['jumlah'] ?? 0;

                            $height = ($maksBuku ?? 0) > 0
                                ? ($jumlah / $maksBuku) * 100
                                : 0;

                            $height = $jumlah > 0
                                ? max(8, $height)
                                : 4;
                        @endphp

                        <span
                            style="height: {{ $height }}%;"
                            title="{{ $hari['hari'] ?? '-' }}: {{ $jumlah }} buku">
                        </span>

                    @empty

                        @for($i = 0; $i < 7; $i++)
                            <span style="height: 4%;"></span>
                        @endfor

                    @endforelse

                </div>

                <small>
                    7 hari
                </small>

            </div>

        </div>

    </a>


    <!-- =================================================
         SEDANG DIPINJAM
    ================================================== -->

    <div class="stat-card stat-green">

        <div class="stat-card-top">

            <div class="stat-icon">
                <i class="fa-solid fa-book-open"></i>
            </div>

            <span class="stat-badge">
                {{ ($sedangDipinjam ?? 0) > 0 ? 'aktif' : 'aman' }}
            </span>

        </div>

        <div class="stat-content">

            <h2>
                {{ number_format($sedangDipinjam ?? 0) }}
            </h2>

            <span>
                Buku Dipinjam
            </span>

        </div>

        <div class="stat-bottom">

            <div class="mini-chart green-chart">

                @forelse($grafikPeminjaman ?? [] as $hari)

                    @php
                        $jumlah = $hari['jumlah'] ?? 0;

                        $height = ($maksPeminjaman ?? 0) > 0
                            ? ($jumlah / $maksPeminjaman) * 100
                            : 0;

                        $height = $jumlah > 0
                            ? max(8, $height)
                            : 4;
                    @endphp

                    <span
                        style="height: {{ $height }}%;"
                        title="{{ $hari['hari'] ?? '-' }}: {{ $jumlah }} peminjaman">
                    </span>

                @empty

                    @for($i = 0; $i < 7; $i++)
                        <span style="height: 4%;"></span>
                    @endfor

                @endforelse

            </div>

            <small>
                7 hari
            </small>

        </div>

    </div>


    <!-- =================================================
         MENUNGGU VERIFIKASI
    ================================================== -->

    <a href="{{ route('petugas.verifikasi') }}" class="stat-card-link">

        <div class="stat-card stat-red">

            <div class="stat-card-top">

                <div class="stat-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>

                <span class="stat-badge">
                    {{ ($totalPending ?? 0) > 0 ? 'perlu dicek' : 'aman' }}
                </span>

            </div>

            <div class="stat-content">

                <h2>
                    {{ number_format($totalPending ?? 0) }}
                </h2>

                <span>
                    Menunggu Verifikasi
                </span>

            </div>

            <div class="stat-bottom">

                <div class="mini-chart red-chart">

                    @forelse($grafikPending ?? [] as $hari)

                        @php
                            $jumlah = $hari['jumlah'] ?? 0;

                            $height = ($maksPending ?? 0) > 0
                                ? ($jumlah / $maksPending) * 100
                                : 0;

                            $height = $jumlah > 0
                                ? max(8, $height)
                                : 4;
                        @endphp

                        <span
                            style="height: {{ $height }}%;"
                            title="{{ $hari['hari'] ?? '-' }}: {{ $jumlah }} pending">
                        </span>

                    @empty

                        @for($i = 0; $i < 7; $i++)
                            <span style="height: 4%;"></span>
                        @endfor

                    @endforelse

                </div>

                <small>
                    7 hari
                </small>

            </div>

        </div>

    </a>

</div>


<!-- =====================================================
     BOTTOM DASHBOARD
===================================================== -->

<div class="dashboard-grid">


    <!-- =================================================
         AKTIVITAS MINGGU INI
    ================================================== -->

    <div class="dashboard-card weekly-card">

        <div class="card-header">

            <div>

                <h3>
                    Aktivitas Minggu Ini
                </h3>

                <p>
                    Jumlah transaksi per hari
                </p>

            </div>

            <span class="period-badge">
                7 Hari Terakhir
            </span>

        </div>


        <div class="weekly-chart">

            <div class="chart-bars">

                @forelse($aktivitasMingguan ?? [] as $hari)

                    @php

                        $jumlah = $hari['jumlah'] ?? 0;

                        $height = ($maksAktivitas ?? 0) > 0
                            ? ($jumlah / $maksAktivitas) * 100
                            : 0;

                        $height = $jumlah > 0
                            ? max(8, $height)
                            : 4;

                    @endphp

                    <div
                        class="chart-column {{ $loop->last ? 'active' : '' }}"
                        title="{{ $hari['hari'] ?? '-' }} | Peminjaman: {{ $hari['peminjaman'] ?? 0 }} | Pengembalian: {{ $hari['pengembalian'] ?? 0 }} | Total: {{ $jumlah }}"
                    >

                        <div
                            class="chart-bar"
                            style="height: {{ $height }}%;">
                        </div>

                        <small>
                            {{ $hari['hari'] ?? '-' }}
                        </small>

                    </div>

                @empty

                    @for($i = 0; $i < 7; $i++)

                        <div class="chart-column">

                            <div
                                class="chart-bar"
                                style="height: 4%;">
                            </div>

                            <small>
                                -
                            </small>

                        </div>

                    @endfor

                @endforelse

            </div>

        </div>

    </div>


    <!-- =================================================
         TOP BUKU
    ================================================== -->

    <div class="dashboard-card top-book-card">

        <div class="card-header">

            <div>

                <h3>
                    📚 Top Buku
                </h3>

                <p>
                    Paling banyak dipinjam
                </p>

            </div>

        </div>


        <div class="top-books">

            @forelse($topBooks ?? [] as $index => $book)

                <div class="book-ranking">

                    <div class="rank-number">
                        {{ $index + 1 }}
                    </div>

                    <div class="book-info">

                        <strong>
                            {{ $book->judul ?? 'Judul tidak tersedia' }}
                        </strong>

                        <small>
                            {{ $book->penulis ?? 'Penulis tidak diketahui' }}
                        </small>

                    </div>

                    <span class="book-count">
                        {{ number_format($book->total_dipinjam ?? 0) }}x
                    </span>

                </div>

            @empty

                <div class="book-ranking">

                    <div class="book-info">

                        <strong>
                            Belum ada data peminjaman
                        </strong>

                        <small>
                            Data akan muncul setelah ada transaksi.
                        </small>

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</div>


<!-- =====================================================
     VERIFIKASI ANGGOTA
===================================================== -->

<div class="dashboard-card verification-card">

    <div class="card-header">

        <div>

            <h3>
                Verifikasi Anggota Terbaru
            </h3>

            <p>
                Daftar anggota yang menunggu proses verifikasi.
            </p>

        </div>

        <a
            href="{{ route('petugas.verifikasi') }}"
            class="btn-approve">

            Lihat Semua

        </a>

    </div>


    <div class="table-responsive">

        <table>

            <thead>

                <tr>

                    <th>Nama</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Aksi</th>

                </tr>

            </thead>


            <tbody>

                @forelse($anggotaPending ?? [] as $user)

                    <tr>

                        <td>
                            <strong>
                                {{ $user->name ?? '-' }}
                            </strong>
                        </td>

                        <td>
                            {{ $user->nis ?? '-' }}
                        </td>

                        <td>
                            {{ $user->kelas ?? '-' }}
                        </td>

                        <td>

                            <span class="badge waiting">
                                {{ ucfirst($user->status ?? 'pending') }}
                            </span>

                        </td>

                        <td>

                            <a
                                href="{{ route('petugas.verifikasi') }}"
                                class="btn-approve">

                                Verifikasi

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="table-empty">

                            Tidak ada anggota yang menunggu verifikasi.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<!-- =====================================================
     AKTIVITAS TERBARU
===================================================== -->

<div class="dashboard-card activity-dashboard-card">

    <div class="card-header">

        <div>

            <h3>
                Aktivitas Terbaru
            </h3>

            <p>
                Riwayat aktivitas sistem perpustakaan.
            </p>

        </div>

        <a
            href="{{ route('petugas.archive.aktivitas') }}"
            class="btn-approve">

            Lihat Semua

        </a>

    </div>


    <div class="activity-list">

        @forelse($activities ?? [] as $activity)

            <div class="activity-item">

                <div class="activity-icon">

                    <i class="fa-solid fa-clock-rotate-left"></i>

                </div>


                <div class="activity-content">

                    <strong>
                        {{ $activity->aktivitas ?? 'Aktivitas sistem' }}
                    </strong>


                    @if(!empty($activity->keterangan))

                        <p>
                            {{ $activity->keterangan }}
                        </p>

                    @endif


                    <small>

                        <i class="fa-solid fa-user"></i>

                        {{ $activity->user?->name ?? 'Sistem' }}

                        &nbsp; • &nbsp;

                        {{ $activity->created_at
                            ? $activity->created_at->diffForHumans()
                            : '-' }}

                    </small>

                </div>

            </div>

        @empty

            <div class="activity-empty">

                <i class="fa-solid fa-inbox"></i>

                <p>
                    Belum ada aktivitas.
                </p>

            </div>

        @endforelse

    </div>

</div>


@endsection


<!-- =====================================================
     DIGITAL CLOCK
===================================================== -->

@push('scripts')

<script>

function updateClock() {

    const now = new Date();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    const clock = document.getElementById('digitalClock');

    if (clock) {

        clock.textContent =
            hours + ' : ' +
            minutes + ' : ' +
            seconds;

    }

}

updateClock();

setInterval(updateClock, 1000);

</script>

@endpush

