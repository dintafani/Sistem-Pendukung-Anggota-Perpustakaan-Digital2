@extends('layouts.petugas')

@section('title', 'Notifikasi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/petugas/notifikasi.css') }}">
@endpush

@section('content')

<div class="notification-page">

    {{-- =====================================================
         ALERT SUCCESS
    ====================================================== --}}

    @if(session('success'))

        <div class="alert-success">

            <i class="fa-solid fa-circle-check"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         ALERT ERROR
    ====================================================== --}}

    @if(session('error'))

        <div class="alert-error">

            <i class="fa-solid fa-circle-xmark"></i>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         WELCOME HEADER
    ====================================================== --}}

    <div class="notification-welcome">

        <div class="notification-welcome-content">

            <div class="notification-welcome-icon">
                <i class="fa-solid fa-bell"></i>
            </div>

            <div class="notification-welcome-text">

                <h1>
                    Notifikasi Petugas
                </h1>

                <p>
                    Pantau seluruh informasi dan pemberitahuan
                    terbaru dari sistem perpustakaan.
                </p>

            </div>

        </div>

        <div class="notification-header-icon">
            <i class="fa-solid fa-bell"></i>
        </div>

    </div>


    {{-- =====================================================
         MAIN CARD
    ====================================================== --}}

    <div class="notification-card">

        {{-- =================================================
             CARD HEADER
        ================================================== --}}

        <div class="notification-card-header">

            <div class="notification-card-title">

                <h2>
                    Daftar Notifikasi
                </h2>

                <p>
                    Informasi terbaru yang diterima petugas.
                </p>

            </div>


            {{-- =================================================
                 ACTION ATAS
            ================================================== --}}

            <div class="notification-header-actions">

                {{-- JUMLAH BELUM DIBACA --}}

                <div class="notification-count">

                    <i class="fa-solid fa-bell"></i>

                    <div class="notification-count-text">

                        <strong>
                            {{ $notifications->where('dibaca', false)->count() }}
                        </strong>

                        <span>
                            Belum Dibaca
                        </span>

                    </div>

                </div>


                {{-- BACA SEMUA --}}

                @if($notifications->where('dibaca', false)->count() > 0)

                    <form
                        action="{{ route('petugas.notifikasi.readAll') }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="read-all-btn"
                        >

                            <i class="fa-solid fa-check-double"></i>

                            <span>
                                Baca Semua
                            </span>

                        </button>

                    </form>

                @endif


                {{-- HAPUS SEMUA --}}

                @if($notifications->count() > 0)

                    <form
                        action="{{ route('petugas.notifikasi.destroyAll') }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus semua notifikasi?')"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="delete-all-btn"
                        >

                            <i class="fa-solid fa-trash"></i>

                            <span>
                                Hapus Semua
                            </span>

                        </button>

                    </form>

                @endif

            </div>

        </div>


        {{-- =====================================================
             LIST NOTIFIKASI
        ====================================================== --}}

        @if($notifications->count() > 0)

            <div class="notification-list">

                @foreach($notifications as $notif)

                    @php

                        $warna = '#3B82F6';
                        $judul = '#2563EB';
                        $icon = 'fa-bell';

                        if ($notif->jenis === 'success') {

                            $warna = '#22C55E';
                            $judul = '#16A34A';
                            $icon = 'fa-circle-check';

                        } elseif ($notif->jenis === 'warning') {

                            $warna = '#F59E0B';
                            $judul = '#D97706';
                            $icon = 'fa-triangle-exclamation';

                        } elseif ($notif->jenis === 'danger') {

                            $warna = '#EF4444';
                            $judul = '#DC2626';
                            $icon = 'fa-circle-xmark';

                        }

                    @endphp


                    {{-- =================================================
                         NOTIFICATION ITEM
                    ================================================== --}}

                    <div
                        class="
                            notification-item
                            {{ $notif->dibaca
                                ? 'notification-read'
                                : 'notification-unread'
                            }}
                        "

                        onclick="bukaNotifikasi(
                            @js($notif->judul),
                            @js($notif->pesan)
                        )"

                        style="cursor:pointer;"
                    >


                        {{-- STATUS --}}

                        <div class="notification-status">

                            @if(!$notif->dibaca)

                                <span class="notification-new-badge">

                                    <span class="new-dot"></span>

                                    BARU

                                </span>

                            @else

                                <span class="notification-read-badge">

                                    <i class="fa-solid fa-check-double"></i>

                                    Sudah Dibaca

                                </span>

                            @endif

                        </div>


                        {{-- ICON --}}

                        <div
                            class="notification-item-icon"
                            style="
                                background: {{ $warna }}15;
                                color: {{ $warna }};
                                border-color: {{ $warna }}35;
                            "
                        >

                            <i class="fa-solid {{ $icon }}"></i>

                        </div>


                        {{-- CONTENT --}}

                        <div class="notification-item-content">

                            <div class="notification-item-top">

                                <div class="notification-title-wrapper">

                                    <h3 style="color: {{ $judul }};">

                                        {{ $notif->judul }}

                                    </h3>

                                    <span class="notification-type">

                                        @if($notif->jenis === 'success')

                                            Berhasil

                                        @elseif($notif->jenis === 'warning')

                                            Peringatan

                                        @elseif($notif->jenis === 'danger')

                                            Penting

                                        @else

                                            Informasi

                                        @endif

                                    </span>

                                </div>


                                <span class="notification-date">

                                    <i class="fa-regular fa-clock"></i>

                                    {{ $notif->created_at->format('d M Y • H:i') }}

                                </span>

                            </div>


                            {{-- PESAN --}}

                            <div class="notification-body">

                                <p>
                                    {{ $notif->pesan }}
                                </p>

                            </div>


                            {{-- ACTION --}}

                            <div
                                class="notification-item-actions"
                                onclick="event.stopPropagation();"
                            >

                                {{-- TANDAI DIBACA --}}

                                @if(!$notif->dibaca)

                                    <form
                                        action="{{ route('petugas.notifikasi.read', $notif->id) }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="read-btn"
                                        >

                                            <i class="fa-solid fa-check"></i>

                                            Tandai Dibaca

                                        </button>

                                    </form>

                                @endif


                                {{-- HAPUS --}}

                                <form
                                    action="{{ route('petugas.notifikasi.destroy', $notif->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus notifikasi ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="delete-btn"
                                    >

                                        <i class="fa-solid fa-trash"></i>

                                        Hapus

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


        @else

            {{-- =====================================================
                 EMPTY STATE
            ====================================================== --}}

            <div class="notification-empty">

                <div class="notification-empty-icon">

                    <i class="fa-regular fa-bell-slash"></i>

                </div>

                <h3>
                    Tidak Ada Notifikasi
                </h3>

                <p>
                    Saat ini belum ada pemberitahuan
                    untuk petugas perpustakaan.
                </p>

            </div>

        @endif

    </div>

</div>


{{-- =====================================================
     JAVASCRIPT
===================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | AUTO HILANG ALERT
    |--------------------------------------------------------------------------
    */

    setTimeout(function () {

        document
            .querySelectorAll('.alert-success, .alert-error')
            .forEach(function (el) {

                el.style.opacity = '0';
                el.style.transform = 'translateY(-5px)';

                setTimeout(function () {

                    el.remove();

                }, 400);

            });

    }, 3000);

});


function bukaNotifikasi(judul, pesan) {

    sessionStorage.setItem(
        'notifikasi_judul',
        judul || ''
    );

    sessionStorage.setItem(
        'notifikasi_pesan',
        pesan || ''
    );


    /*
    |--------------------------------------------------------------------------
    | KE HALAMAN VERIFIKASI
    |--------------------------------------------------------------------------
    */

    window.location.href =
        "{{ route('petugas.verifikasi') }}";
}

</script>

@endsection