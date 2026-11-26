@extends('layouts.siswa')

@section('title','Notifikasi')

@section('styles')

<link rel="stylesheet"
      href="{{ asset('css/siswa/notifikasi.css') }}">

@endsection

@section('content')

{{-- =========================================================
     ALERT
========================================================= --}}

@if(session('success'))

<div class="alert-success">

    <i class="fa-solid fa-circle-check"></i>

    <span>{{ session('success') }}</span>

</div>

@endif

@if(session('error'))

<div class="alert-error">

    <i class="fa-solid fa-circle-xmark"></i>

    <span>{{ session('error') }}</span>

</div>

@endif


<div class="notification-page">

    {{-- =====================================================
         HERO
    ====================================================== --}}

    <div class="notification-hero">

        <div class="hero-left">

            <div class="hero-icon">

                <i class="fa-solid fa-bell"></i>

            </div>

            <div>

                <h1>Notifikasi</h1>

                <p>

                    Semua aktivitas perpustakaan,
                    peminjaman,
                    pengembalian,
                    reward,
                    serta informasi terbaru akan muncul di sini.

                </p>

            </div>

        </div>

        <div class="hero-right">

            <div class="hero-count">

                <span>Total</span>

                <h2>{{ $notifications->count() }}</h2>

                <small>Notifikasi</small>

            </div>

        </div>

    </div>


    {{-- =====================================================
         STATISTIK
    ====================================================== --}}

    <div class="notification-stats">

        <div class="stat-card blue">

            <div class="stat-icon">

                <i class="fa-solid fa-bell"></i>

            </div>

            <div>

                <h3>{{ $notifications->count() }}</h3>

                <span>Total Notifikasi</span>

            </div>

        </div>

        <div class="stat-card green">

            <div class="stat-icon">

                <i class="fa-solid fa-envelope-open"></i>

            </div>

            <div>

                <h3>

                    {{ $notifications->where('dibaca',true)->count() }}

                </h3>

                <span>Sudah Dibaca</span>

            </div>

        </div>

        <div class="stat-card yellow">

            <div class="stat-icon">

                <i class="fa-solid fa-envelope"></i>

            </div>

            <div>

                <h3>

                    {{ $notifications->where('dibaca',false)->count() }}

                </h3>

                <span>Belum Dibaca</span>

            </div>

        </div>

        <div class="stat-card red">

            <div class="stat-icon">

                <i class="fa-solid fa-circle-exclamation"></i>

            </div>

            <div>

                <h3>

                    {{ $notifications->where('jenis','danger')->count() }}

                </h3>

                <span>Penting</span>

            </div>

        </div>

    </div>


    {{-- =====================================================
         HEADER LIST
    ====================================================== --}}

    <div class="notification-header">

        <div>

            <h2>Aktivitas Terbaru</h2>

            <p>

                Kelola semua notifikasi perpustakaan Anda.

            </p>

        </div>

        <div style="display:flex;gap:10px;">

            @if($notifications->where('dibaca',false)->count())

            <form action="{{ route('siswa.notifikasi.readAll') }}"
                  method="POST">

                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="read-all-btn">

                    <i class="fa-solid fa-check-double"></i>

                    Tandai Semua

                </button>

            </form>

            @endif

            @if($notifications->count())

            <form
                action="{{ route('siswa.notifikasi.destroyAll') }}"
                method="POST"
                onsubmit="return confirm('Hapus semua notifikasi?')">

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="delete-all-btn">

                    <i class="fa-solid fa-trash"></i>

                    Hapus Semua

                </button>

            </form>

            @endif

        </div>

    </div>


    {{-- =====================================================
         LIST NOTIFIKASI
    ====================================================== --}}

    <div class="notification-list">

        @forelse($notifications as $notif)

            @php

                $warna='#3B82F6';
                $judul='#2563EB';
                $icon='fa-bell';

                if($notif->jenis=='success'){
                    $warna='#22C55E';
                    $judul='#16A34A';
                    $icon='fa-circle-check';
                }

                if($notif->jenis=='warning'){
                    $warna='#F59E0B';
                    $judul='#D97706';
                    $icon='fa-triangle-exclamation';
                }

                if($notif->jenis=='danger'){
                    $warna='#EF4444';
                    $judul='#DC2626';
                    $icon='fa-circle-xmark';
                }

            @endphp
                        <div class="notification-card {{ $notif->dibaca ? 'read' : 'unread' }}">

                {{-- Badge Status --}}
                @if(!$notif->dibaca)

                    <span class="new-badge">
                        BARU
                    </span>

                @else

                    <span class="read-badge">

                        <i class="fa-solid fa-check-double"></i>

                        Sudah Dibaca

                    </span>

                @endif


                {{-- ============================
                     HEADER KARTU
                ============================= --}}

                <div class="notification-top">

                    <div class="notification-left">

                        <div class="notification-icon"
                             style="background:{{ $warna }}20;color:{{ $warna }};">

                            <i class="fa-solid {{ $icon }}"></i>

                        </div>

                        <div class="notification-info">

                            <div class="notification-title">

                                <h3 style="color:{{ $judul }}">

                                    {{ $notif->judul }}

                                </h3>

                                <span class="notification-type">

                                    @if($notif->jenis=='success')

                                        Berhasil

                                    @elseif($notif->jenis=='warning')

                                        Peringatan

                                    @elseif($notif->jenis=='danger')

                                        Penting

                                    @else

                                        Informasi

                                    @endif

                                </span>

                            </div>

                            <div class="notification-time">

                                <i class="fa-regular fa-clock"></i>

                                {{ $notif->created_at->format('d M Y • H:i') }}

                            </div>

                        </div>

                    </div>


                    {{-- ============================
                         AKSI
                    ============================= --}}

                    <div class="notification-action">

                        @if(!$notif->dibaca)

                        <form action="{{ route('siswa.notifikasi.read',$notif->id) }}"
                              method="POST">

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="read-btn">

                                <i class="fa-solid fa-check"></i>

                                Tandai Dibaca

                            </button>

                        </form>

                        @endif


                        <form
                            action="{{ route('siswa.notifikasi.destroy',$notif->id) }}"
                            method="POST"
                            onsubmit="return confirm('Hapus notifikasi ini?')">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="delete-btn">

                                <i class="fa-solid fa-trash"></i>

                                Hapus

                            </button>

                        </form>

                    </div>

                </div>


                {{-- ============================
                     ISI NOTIFIKASI
                ============================= --}}

                <div class="notification-body">

                    <p>

                        {{ $notif->pesan }}

                    </p>

                </div>

            </div>

        @empty

            <div class="empty-notification">

                <div class="empty-icon">

                    <i class="fa-regular fa-bell-slash"></i>

                </div>

                <h2>

                    Belum Ada Notifikasi

                </h2>

                <p>

                    Semua aktivitas perpustakaan,
                    peminjaman,
                    pengembalian,
                    reward,
                    maupun informasi terbaru
                    akan muncul di halaman ini.

                </p>

            </div>

        @endforelse

    </div>
        {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="notification-footer">

        <div class="footer-card">

            <div class="footer-icon">

                📚

            </div>

            <div class="footer-content">

                <h3>

                    Jangan Lewatkan Informasi Penting

                </h3>

                <p>

                    Semua aktivitas perpustakaan,
                    mulai dari peminjaman,
                    pengembalian,
                    poin literasi,
                    reward,
                    hingga pengumuman terbaru
                    akan muncul pada halaman ini.

                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
         TIPS
    ====================================================== --}}

    <div class="tips-section">

        <div class="tips-title">

            <i class="fa-solid fa-lightbulb"></i>

            <h3>

                Tips Menggunakan Notifikasi

            </h3>

        </div>

        <div class="tips-grid">

            <div class="tip-card">

                <div class="tip-icon">

                    🔔

                </div>

                <h4>

                    Cek Secara Berkala

                </h4>

                <p>

                    Jangan sampai melewatkan
                    informasi penting dari
                    perpustakaan.

                </p>

            </div>

            <div class="tip-card">

                <div class="tip-icon">

                    📚

                </div>

                <h4>

                    Rajin Membaca

                </h4>

                <p>

                    Semakin aktif membaca,
                    semakin banyak poin
                    yang dapat dikumpulkan.

                </p>

            </div>

            <div class="tip-card">

                <div class="tip-icon">

                    🏆

                </div>

                <h4>

                    Naikkan Badge

                </h4>

                <p>

                    Kumpulkan poin literasi
                    untuk memperoleh
                    badge yang lebih tinggi.

                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
         QUOTE
    ====================================================== --}}

    <div class="quote-card">

        <i class="fa-solid fa-quote-left"></i>

        <blockquote>

            "Setiap buku yang kamu baca adalah
            satu langkah menuju masa depan
            yang lebih baik."

        </blockquote>

    </div>

</div>


{{-- =====================================================
     AUTO HILANG ALERT
===================================================== --}}

<script>

setTimeout(function(){

    document.querySelectorAll('.alert-success,.alert-error')
    .forEach(function(el){

        el.style.opacity='0';

        setTimeout(function(){

            el.remove();

        },400);

    });

},3000);

</script>

@endsection