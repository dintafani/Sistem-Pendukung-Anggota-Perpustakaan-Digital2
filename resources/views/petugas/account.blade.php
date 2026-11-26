@extends('layouts.petugas')

@section('title','Akun Petugas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/petugas/akun.css') }}">
@endpush

@section('content')

<div class="account-page">



    {{-- =====================================================
         ACCOUNT CARD
    ====================================================== --}}
    <div class="account-card">

        <div class="account-card-header">

            <div>

                <h2>
                    <i class="fa-solid fa-user-gear"></i>
                    Informasi Akun
                </h2>

                <p>
                    Perbarui data akun Anda di bawah ini.
                </p>

            </div>

        </div>


        {{-- =================================================
             SUCCESS MESSAGE
        ================================================== --}}
        @if(session('success'))

            <div class="account-alert success-alert">

                <div class="alert-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div>
                    <strong>Berhasil</strong>

                    <p>
                        {{ session('success') }}
                    </p>
                </div>

            </div>

        @endif


        {{-- =================================================
             ERROR MESSAGE
        ================================================== --}}
        @if ($errors->any())

            <div class="account-alert error-alert">

                <div class="alert-icon">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>

                <div>

                    <strong>Terjadi Kesalahan</strong>

                    <ul>

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        @endif


        {{-- =================================================
             FORM
        ================================================== --}}
        <form
            action="{{ route('petugas.account.update') }}"
            method="POST"
            class="account-form"
        >

            @csrf


            {{-- NAMA --}}
            <div class="form-group">

                <label for="name">

                    <i class="fa-solid fa-user"></i>

                    Nama Petugas

                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name',$user->name) }}"
                    required
                    autocomplete="name"
                    placeholder="Masukkan nama petugas"
                >

            </div>


            {{-- EMAIL --}}
            <div class="form-group">

                <label for="email">

                    <i class="fa-solid fa-envelope"></i>

                    Email

                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email',$user->email) }}"
                    required
                    autocomplete="email"
                    placeholder="Masukkan email"
                >

            </div>

            {{-- NOMOR TELEPON --}}
<div class="form-group">

    <label for="no_telepon">

        <i class="fa-solid fa-phone"></i>

        Nomor Telepon

    </label>

    <input
        id="no_telepon"
        type="text"
        name="no_telepon"
        value="{{ old('no_telepon', $user->no_telepon) }}"
        autocomplete="tel"
        placeholder="Masukkan nomor telepon"
        inputmode="numeric"
    >

    <small class="form-help">
        Nomor Telepon dapat diubah jika diperlukan.
    </small>

</div>


            {{-- ROLE --}}
            <div class="form-group">

                <label for="role">

                    <i class="fa-solid fa-id-badge"></i>

                    Role

                </label>

                <div class="readonly-wrapper">

                    <input
                        id="role"
                        type="text"
                        value="Petugas Perpustakaan"
                        readonly
                    >

                    <span class="readonly-badge">
                        <i class="fa-solid fa-lock"></i>
                        Tetap
                    </span>

                </div>

                <small class="form-help">
                    Role akun tidak dapat diubah dari halaman ini.
                </small>

            </div>


            {{-- PASSWORD --}}
            <div class="form-section-title">

                <div class="section-line"></div>

                <span>
                    <i class="fa-solid fa-lock"></i>
                    Keamanan Akun
                </span>

                <div class="section-line"></div>

            </div>


            <div class="form-group">

                <label for="password">

                    <i class="fa-solid fa-key"></i>

                    Password Baru

                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="Kosongkan jika tidak ingin mengubah password"
                >

                <small class="form-help">
                    Isi hanya jika ingin mengganti password akun.
                </small>

            </div>


            {{-- KONFIRMASI PASSWORD --}}
            <div class="form-group">

                <label for="password_confirmation">

                    <i class="fa-solid fa-shield-halved"></i>

                    Konfirmasi Password Baru

                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Ulangi password baru"
                >

            </div>


            {{-- BUTTON --}}
            <div class="account-actions">

                <button
                    type="submit"
                    class="account-save-btn"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan Perubahan

                </button>


                <a
                    href="{{ route('petugas.dashboard') }}"
                    class="account-back-btn"
                >

                    <i class="fa-solid fa-arrow-left"></i>

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>

@endsection