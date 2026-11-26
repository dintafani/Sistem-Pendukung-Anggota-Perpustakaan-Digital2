@extends('layouts.petugas')

@section('title', 'Verifikasi Anggota')


{{-- =====================================================
    CSS KHUSUS VERIFIKASI
===================================================== --}}

@section('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/petugas/verifikasi.css') }}"
>

@endsection


@section('content')


{{-- =====================================================
    NOTIFIKASI SUCCESS
===================================================== --}}

@if(session('success'))

<div class="verification-alert success-alert">

    <div class="alert-icon">
        <i class="fa-solid fa-check"></i>
    </div>

    <div>
        <strong>Berhasil</strong>

        <p>
            {{ session('success') }}
        </p>
    </div>

</div>

@endif



{{-- =====================================================
    HEADER
===================================================== --}}

<div class="verification-header">


    {{-- LEFT --}}

    <div class="verification-title">

        <div class="verification-title-icon">

            <i class="fa-solid fa-check"></i>

        </div>

        <div>

            <h1>
                Verifikasi Anggota
            </h1>

            <p>
                Kelola dan verifikasi pendaftaran anggota baru
            </p>

        </div>

    </div>


    {{-- RIGHT --}}

    <div class="verification-counter">

        <span class="counter-dot"></span>

        <strong>
            {{ $anggotaPending->count() }}
            Menunggu Verifikasi
        </strong>

    </div>

</div>



{{-- =====================================================
    TOOLBAR
===================================================== --}}

<div class="verification-toolbar">


    {{-- SEARCH --}}

    <div class="verification-search">

        <i class="fa-solid fa-magnifying-glass"></i>

        <input
            type="text"
            id="searchAnggota"
            placeholder="Cari nama anggota..."
            autocomplete="off"
        >

    </div>


    {{-- TAB --}}

    <div class="verification-tabs">

        <button
            type="button"
            class="verification-tab active"
            data-tab="pending"
        >

            Pending
            ({{ $anggotaPending->count() }})

        </button>


        <button
            type="button"
            class="verification-tab"
            data-tab="verified"
        >

            Terverifikasi
            ({{ $anggotaAktif->count() }})

        </button>

    </div>

</div>



{{-- =====================================================
    TAB PENDING
===================================================== --}}

<div
    class="verification-panel active"
    id="panel-pending"
>


    <div class="member-grid">


        @forelse($anggotaPending as $index => $user)


        {{-- =================================================
            MEMBER CARD
        ================================================== --}}

        <div
            class="member-card member-card-{{ ($index % 4) + 1 }}"
            data-name="{{ strtolower($user->name) }}"
        >


            {{-- CARD HEADER --}}

            <div class="member-card-header">


                {{-- AVATAR --}}

                <div class="member-avatar">

                    {{ strtoupper(substr($user->name, 0, 2)) }}

                </div>


                {{-- DATA --}}

                <div class="member-main-info">

                    <h3>
                        {{ $user->name }}
                    </h3>

                    <p>
                        {{ $user->email }}
                    </p>

                </div>


                {{-- TYPE --}}

                <span class="member-type">

                    {{ $user->role == 'siswa' ? 'Pelajar' : 'Umum' }}

                </span>

            </div>



            {{-- DATA INFO --}}

            <div class="member-info-box">


                <div class="member-info-item">

                    <span>
                        ID ANGGOTA
                    </span>

                    <strong>
                        {{ $user->barcode ?? 'A-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                    </strong>

                </div>


                <div class="member-info-item">

                    <span>
                        DAFTAR
                    </span>

                    <strong class="date-text">

                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}

                    </strong>

                </div>


            </div>



            {{-- ACTION --}}

            <div class="member-actions">


                {{-- SETUJUI --}}

                <form
                    action="{{ route('petugas.verifikasi.setujui', $user->id) }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="submit"
                        class="member-btn approve-btn"
                    >

                        <i class="fa-solid fa-check"></i>

                        Setujui

                    </button>

                </form>



                {{-- TOLAK --}}

                <form
                    action="{{ route('petugas.verifikasi.tolak', $user->id) }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="submit"
                        class="member-btn reject-btn"
                    >

                        <i class="fa-solid fa-xmark"></i>

                        Tolak

                    </button>

                </form>


            </div>


        </div>


        @empty


        <div class="verification-empty">

            <div class="empty-icon">

                <i class="fa-solid fa-user-check"></i>

            </div>

            <h3>
                Tidak ada anggota pending
            </h3>

            <p>
                Semua pendaftaran anggota sudah diproses.
            </p>

        </div>


        @endforelse


    </div>

</div>



{{-- =====================================================
    TAB TERFERIFIKASI
===================================================== --}}

<div
    class="verification-panel"
    id="panel-verified"
>


    <div class="member-grid">


        @forelse($anggotaAktif as $index => $user)


        <div
            class="member-card verified-card member-card-{{ ($index % 4) + 1 }}"
            data-name="{{ strtolower($user->name) }}"
        >


            {{-- HEADER --}}

            <div class="member-card-header">


                <div class="member-avatar">

                    {{ strtoupper(substr($user->name, 0, 2)) }}

                </div>


                <div class="member-main-info">

                    <h3>
                        {{ $user->name }}
                    </h3>

                    <p>
                        {{ $user->email }}
                    </p>

                </div>


                <span class="member-type verified-type">

                    Aktif

                </span>


            </div>



            {{-- INFO --}}

            <div class="member-info-box">


                <div class="member-info-item">

                    <span>
                        ID ANGGOTA
                    </span>

                    <strong>
                        {{ $user->barcode ?? 'A-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                    </strong>

                </div>


                <div class="member-info-item">

                    <span>
                        DAFTAR
                    </span>

                    <strong class="date-text">

                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}

                    </strong>

                </div>


            </div>



            {{-- STATUS --}}

            <div class="verified-status">

                <i class="fa-solid fa-circle-check"></i>

                Anggota Aktif

            </div>


        </div>


        @empty


        <div class="verification-empty">

            <div class="empty-icon">

                <i class="fa-solid fa-users"></i>

            </div>

            <h3>
                Belum ada anggota aktif
            </h3>

            <p>
                Anggota yang telah disetujui akan muncul di sini.
            </p>

        </div>


        @endforelse


    </div>

</div>



{{-- =====================================================
    JAVASCRIPT
===================================================== --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | TAB
    |--------------------------------------------------------------------------
    */

    const tabs =
        document.querySelectorAll('.verification-tab');

    const panels =
        document.querySelectorAll('.verification-panel');


    tabs.forEach(function(tab) {

        tab.addEventListener('click', function() {

            const target =
                this.dataset.tab;


            tabs.forEach(function(item) {

                item.classList.remove('active');

            });


            panels.forEach(function(panel) {

                panel.classList.remove('active');

            });


            this.classList.add('active');


            const panel =
                document.getElementById(
                    'panel-' + target
                );


            if(panel) {

                panel.classList.add('active');

            }

            filterAnggota();

        });

    });



    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    const search =
        document.getElementById('searchAnggota');


    if(search) {

        search.addEventListener('input', function() {

            filterAnggota();

        });

    }



    /*
    |--------------------------------------------------------------------------
    | FILTER ANGGOTA DARI NOTIFIKASI
    |--------------------------------------------------------------------------
    */

    function filterAnggota() {

        const keyword =
            search
                ? search.value.toLowerCase().trim()
                : '';


        const activePanel =
            document.querySelector(
                '.verification-panel.active'
            );


        if(!activePanel) {

            return;

        }


        const cards =
            activePanel.querySelectorAll(
                '.member-card'
            );


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA DARI NOTIFIKASI
        |--------------------------------------------------------------------------
        */

        const notificationMessage =
            sessionStorage.getItem(
                'notifikasi_pesan'
            );


        const notificationTitle =
            sessionStorage.getItem(
                'notifikasi_judul'
            );




        if(!notificationMessage && !notificationTitle) {


            cards.forEach(function(card) {

                card.style.display = '';

                card.classList.remove(
                    'notification-target'
                );

            });


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | GABUNG JUDUL + PESAN
        |--------------------------------------------------------------------------
        */

        const notificationText =
            (
                notificationTitle +
                ' ' +
                notificationMessage
            ).toLowerCase();


        let ditemukan = false;


        /*
        |--------------------------------------------------------------------------
        | CEK SETIAP ANGGOTA
        |--------------------------------------------------------------------------
        */

        cards.forEach(function(card) {


            const nama =
                (
                    card.dataset.name || ''
                ).toLowerCase().trim();


            /*
            |--------------------------------------------------------------------------
            | FILTER SEARCH MANUAL
            |--------------------------------------------------------------------------
            */

            const cocokSearch =
                nama.includes(keyword);


            /*
            |--------------------------------------------------------------------------
            | CEK APAKAH NAMA ADA DI NOTIFIKASI
            |--------------------------------------------------------------------------
            */

            const cocokNotifikasi =
                notificationText.includes(nama);


            /*
            |--------------------------------------------------------------------------
            | HASIL
            |--------------------------------------------------------------------------
            */

            if(cocokSearch && cocokNotifikasi) {


                card.style.display = '';

                card.classList.add(
                    'notification-target'
                );


                ditemukan = true;


            } else {


                card.style.display = 'none';

                card.classList.remove(
                    'notification-target'
                );

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Jika tidak ditemukan
        |--------------------------------------------------------------------------
        */

        if(!ditemukan) {

            console.log(
                'Anggota dari notifikasi tidak ditemukan.'
            );

        }

    }



    /*
    |--------------------------------------------------------------------------
    | JALANKAN FILTER OTOMATIS SAAT HALAMAN DIBUKA
    |--------------------------------------------------------------------------
    */

    filterAnggota();



    /*
    |--------------------------------------------------------------------------
    | HAPUS DATA NOTIFIKASI SETELAH DIGUNAKAN
    |--------------------------------------------------------------------------
    */

    setTimeout(function() {

        sessionStorage.removeItem(
            'notifikasi_judul'
        );

        sessionStorage.removeItem(
            'notifikasi_pesan'
        );

    }, 1000);

});

</script>

@endpush

@endsection