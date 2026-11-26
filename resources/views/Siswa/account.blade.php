@extends('layouts.siswa')

@section('title', 'Akun Saya')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/siswa/akun.css') }}">
@endsection

@section('content')

@if(session('success'))
    <div class="alert-success">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
@endif


<!-- ============================================================
     HERO PROFILE
============================================================ -->

<div class="profile-hero">

    <div class="profile-left">

        <!-- FOTO PROFIL -->

        <div class="profile-avatar-wrapper">

            @if($user->foto)

                <img
                    src="{{ asset('storage/'.$user->foto) }}"
                    class="profile-avatar"
                    alt="Foto Profil"
                >

            @else

                <div class="profile-avatar placeholder">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>

            @endif

            <label
                for="foto"
                class="upload-photo"
                title="Ganti Foto Profil"
            >
                <i class="fa-solid fa-camera"></i>
            </label>

        </div>


        <!-- INFORMASI PROFIL -->

        <div class="profile-info">

            <div class="profile-name">

                <h2>
                    {{ $user->name }}
                </h2>


                {{-- STATUS MENGIKUTI DATABASE --}}

                @if($user->status)

                    <span class="status-badge">

                        @if($user->status == 'diterima')
                            <i class="fa-solid fa-circle-check"></i>
                        @elseif($user->status == 'pending')
                            <i class="fa-solid fa-clock"></i>
                        @elseif($user->status == 'menunggu')
                            <i class="fa-solid fa-clock"></i>
                        @elseif($user->status == 'aktif')
                            <i class="fa-solid fa-circle-check"></i>
                        @else
                            <i class="fa-solid fa-circle-info"></i>
                        @endif

                        {{ ucfirst($user->status) }}

                    </span>

                @endif

            </div>


            <div class="member-text">

                <i class="fa-solid fa-book-open"></i>

                Anggota Perpustakaan

                @if($user->sekolah)
                    • {{ $user->sekolah }}
                @endif

            </div>


            <!-- STATISTIK -->

            <div class="hero-stats">

                <div class="hero-stat">

                    <div class="hero-stat-icon">
                        ⭐
                    </div>

                    <div>
                        <h3>
                            {{ number_format($user->poin) }}
                        </h3>

                        <small>
                            Total Poin
                        </small>
                    </div>

                </div>


                <div class="hero-stat">

                    <div class="hero-stat-icon">
                        🏆
                    </div>

                    <div>
                        <h3>
                            #{{ $ranking }}
                        </h3>

                        <small>
                            Ranking
                        </small>
                    </div>

                </div>


                <div class="hero-stat">

                    <div class="hero-stat-icon">
                        📚
                    </div>

                    <div>
                        <h3>
                            {{ $sedangDipinjam }}
                        </h3>

                        <small>
                            Buku Dipinjam
                        </small>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- KARTU ANGGOTA -->

    <div class="profile-right">

        <div class="member-card">

            <div class="member-card-icon">
                <i class="fa-solid fa-id-card"></i>
            </div>

            <div>
                <strong>
                    Kartu Anggota
                </strong>

                <small>
                    Perpustakaan Digital
                </small>
            </div>

        </div>

    </div>

</div>



<!-- ============================================================
     FORM UPDATE AKUN
============================================================ -->

<form
    action="{{ route('siswa.account.update') }}"
    method="POST"
    enctype="multipart/form-data"
>

    @csrf
    @method('PUT')

    <input
        type="file"
        id="foto"
        name="foto"
        hidden
    >


    <!-- ========================================================
         GRID UTAMA
    ========================================================= -->

    <div class="account-grid">


        <!-- ====================================================
             KIRI - DATA DIRI
        ===================================================== -->

        <div class="account-left">

            <div class="account-card">

                <div class="card-title">

                    <div class="card-title-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div>

                        <h3>
                            Data Diri
                        </h3>

                        <p>
                            Informasi akun perpustakaan kamu
                        </p>

                    </div>

                </div>


                <!-- DATA -->

                <div class="info-list">

                    <div class="info-item">

                        <span>
                            Nama Lengkap
                        </span>

                        <strong>
                            {{ $user->name }}
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Email
                        </span>

                        <strong>
                            {{ $user->email }}
                        </strong>

                    </div>
<label>
    Nomor HP / WhatsApp
</label>

<input
    type="text"
    name="no_telepon"
    class="form-input"
    value="{{ old('no_telepon', $user->no_telepon) }}"
    placeholder="Contoh: 081234567890"
    maxlength="15"
>

                    <div class="info-item">

                        <span>
                            NIS
                        </span>

                        <strong>
                            {{ $user->nis ?? '-' }}
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Kelas
                        </span>

                        <strong>
                            {{ $user->kelas ?? '-' }}
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Status Akun
                        </span>

                        <strong>
                            {{ $user->status ?? '-' }}
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            Badge Literasi
                        </span>

                        <strong class="badge-text">
                            {{ $user->badge ?? 'Pemula' }}
                        </strong>

                    </div>


                    <div class="info-item">

                        <span>
                            QR Code Anggota
                        </span>

                        <strong>
                            {{ $user->barcode ?? '-' }}
                        </strong>

                    </div>

                </div>



                <!-- EDIT DATA -->

                <div class="edit-section">

                    <div class="edit-title">

                        <i class="fa-solid fa-pen"></i>

                        <span>
                            Edit Informasi
                        </span>

                    </div>


                    <label>
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name', $user->name) }}"
                        required
                    >


                    <label>
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-input"
                        value="{{ old('email', $user->email) }}"
                        required
                    >
<label>
    Kelas
</label>

<input
    type="text"
    name="kelas"
    class="form-input"
    value="{{ old('kelas', $user->kelas) }}"
    required
>

                    <button
                        type="submit"
                        class="save-btn"
                    >

                        <i class="fa-solid fa-floppy-disk"></i>

                        Simpan Perubahan

                    </button>

                </div>

            </div>

        </div>



        <!-- ====================================================
             KANAN
        ===================================================== -->

        <div class="account-right">


            <!-- =================================================
                 PROGRESS LITERASI
            ================================================== -->

            <div class="account-card">

                <div class="card-title">

                    <div class="card-title-icon orange">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>

                    <div>

                        <h3>
                            Progress Literasi
                        </h3>

                        <p>
                            Perjalanan poin literasi kamu
                        </p>

                    </div>

                </div>


                @php

                    $poin = $user->poin ?? 0;

                    if($poin < 50){

                        $nextBadge = 'Anggota Baru';
                        $target = 50;

                    } elseif($poin < 100){

                        $nextBadge = 'Pembaca Aktif';
                        $target = 100;

                    } elseif($poin < 200){

                        $nextBadge = 'Master Literasi';
                        $target = 200;

                    } else {

                        $nextBadge = 'Legend Literasi';
                        $target = 300;

                    }

                    $progress = min(($poin / $target) * 100, 100);

                @endphp


                <div class="level-box">

                    <div class="level-header">

                        <div>

                            <span class="current-badge">
                                {{ $user->badge ?? 'Pemula' }}
                            </span>

                            <small>
                                Badge Saat Ini
                            </small>

                        </div>

                        <div class="point-total">

                            <strong>
                                {{ number_format($poin) }}
                            </strong>

                            <span>
                                Poin
                            </span>

                        </div>

                    </div>


                    <div class="progress">

                        <div
                            class="progress-bar"
                            style="width: {{ $progress }}%;"
                        ></div>

                    </div>


                    <div class="progress-info">

                        <span>
                            Berikutnya:
                            <strong>{{ $nextBadge }}</strong>
                        </span>

                        <span>
                            {{ max($target - $poin, 0) }} poin lagi
                        </span>

                    </div>

                </div>



                <!-- MINI STAT -->

                <div class="stats-grid">

                    <div class="stat-card">

                        <div class="stat-icon">
                            ⭐
                        </div>

                        <h3>
                            {{ number_format($user->poin) }}
                        </h3>

                        <span>
                            Total Poin
                        </span>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon">
                            🏆
                        </div>

                        <h3>
                            #{{ $ranking }}
                        </h3>

                        <span>
                            Ranking
                        </span>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon">
                            📖
                        </div>

                        <h3>
                            {{ $jumlahDipinjam }}
                        </h3>

                        <span>
                            Buku
                        </span>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 QR CODE
            ================================================== -->

            <div class="account-card member-card-box">

                <div class="card-title">

                    <div class="card-title-icon orange">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>

                    <div>

                        <h3>
                            Kartu Anggota
                        </h3>

                        <p>
                            QR Code untuk transaksi perpustakaan
                        </p>

                    </div>

                </div>


                <div class="member-card-preview">


                    <!-- IDENTITAS -->

                    <div class="member-profile">

                        @if($user->foto)

                            <img
                                src="{{ asset('storage/'.$user->foto) }}"
                                class="member-avatar"
                                alt="Avatar"
                            >

                        @else

                            <div class="member-avatar placeholder">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>

                        @endif


                        <div>

                            <h4>
                                {{ $user->name }}
                            </h4>

                            <span>
                                NIS {{ $user->nis ?? '-' }}
                            </span>

                            <small>
                                {{ $user->kelas ?? '-' }}
                            </small>

                        </div>

                    </div>



                    <!-- QR -->

                    @if($user->barcode && $qrCode)

                        <div class="qr-wrapper">

                            <img
                                src="data:image/png;base64,{{ $qrCode }}"
                                class="qr-image"
                                alt="QR Code"
                            >

                        </div>


                        <div class="barcode-number">

                            {{ $user->barcode }}

                        </div>


                        <p class="qr-description">

                            Tunjukkan QR Code ini kepada petugas
                            untuk melakukan peminjaman atau pengembalian buku.

                        </p>


                        <button
                            type="button"
                            class="download-btn"
                            onclick="downloadQR()"
                        >

                            <i class="fa-solid fa-download"></i>

                            Download QR Code

                        </button>


                    @else

                        <div class="empty-qr">

                            <i class="fa-solid fa-qrcode"></i>

                            <h4>
                                QR Code Belum Tersedia
                            </h4>

                            <p>
                                QR Code akan muncul setelah akun
                                diproses oleh petugas perpustakaan.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</form>



<!-- ============================================================
     RIWAYAT POIN
============================================================ -->

<div class="history-section">


    <div class="history-header">

        <div>

            <div class="history-title">

                <div class="history-title-icon">
                    ⭐
                </div>

                <div>

                    <h3>
                        Riwayat Poin Literasi
                    </h3>

                    <p>
                        3 aktivitas poin terbaru kamu
                    </p>

                </div>

            </div>

        </div>


        <div class="history-summary">

            <div class="summary-card">

                <small>
                    Total Poin
                </small>

                <strong>
                    {{ number_format($user->poin) }}
                </strong>

            </div>


            <div class="summary-card">

                <small>
                    Total Aktivitas
                </small>

                <strong>
                    {{ $historiPoin->count() }}
                </strong>

            </div>

        </div>

    </div>



    <!-- ========================================================
         HISTORY
    ========================================================= -->

    @if($historiPoin->count() > 0)

        <div class="history-list">

            @foreach($historiPoin->sortByDesc('created_at')->take(3) as $item)

                <div class="history-card">


                    <div class="history-icon">

                        @if($item->judul == 'Poin Bertambah')

                            ⭐

                        @else

                            🎁

                        @endif

                    </div>


                    <div class="history-content">

                        <h4>
                            {{ $item->judul }}
                        </h4>

                        <small>

                            <i class="fa-regular fa-calendar"></i>

                            {{ $item->created_at->format('d M Y, H:i') }}

                        </small>

                        <p>
                            {{ $item->pesan }}
                        </p>

                    </div>


                    @if($item->judul == 'Poin Bertambah')

                        <div class="point plus">
                            +10
                        </div>

                    @else

                        <div class="point reward">
                            Reward
                        </div>

                    @endif

                </div>

            @endforeach

        </div>


        <!-- ====================================================
             LIHAT SEMUA
        ===================================================== -->

        @if($historiPoin->count() > 3)

            <div class="history-footer">

                <a
                    href="{{ url('/siswa/history') }}"
                    class="view-history-btn"
                >

                    Lihat Semua Histori Poin

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>

        @endif


    @else

        <div class="empty-history-card">

            <div class="empty-history-icon">
                📚
            </div>

            <h3>
                Belum Ada Riwayat Poin
            </h3>

            <p>
                Aktivitas poin literasi kamu akan muncul di sini
                setelah melakukan aktivitas membaca.
            </p>

        </div>

    @endif

</div>



<!-- ============================================================
     JAVASCRIPT DOWNLOAD QR
============================================================ -->

<script>

function downloadQR() {

    const link = document.createElement('a');

    link.href = "data:image/png;base64,{{ $qrCode }}";

    link.download = "QR-{{ $user->barcode }}.png";

    document.body.appendChild(link);

    link.click();

    document.body.removeChild(link);

}

</script>


@endsection