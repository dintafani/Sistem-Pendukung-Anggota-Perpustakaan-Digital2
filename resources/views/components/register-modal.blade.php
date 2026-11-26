{{-- =========================================================
     POPUP REGISTER
========================================================= --}}

<div
    id="registerModal"
    class="register-modal"
    aria-hidden="true"
>

    <div
        class="register-popup"
        role="dialog"
        aria-modal="true"
        aria-labelledby="registerTitle"
    >

        {{-- =====================================================
             CLOSE BUTTON
        ====================================================== --}}

        <button
            type="button"
            class="register-popup-close"
            id="closeRegister"
            aria-label="Tutup"
        >
            ✕
        </button>


        {{-- =====================================================
             HEADER REGISTER
        ====================================================== --}}

        <div class="register-popup-header">

            {{-- ICON --}}
            <div class="register-popup-icon">
                📚
            </div>

            {{-- TITLE --}}
            <h2 id="registerTitle">
                Buat Akun Baru
            </h2>

            {{-- SUBTITLE --}}
            <p>
                Bergabung dengan Perpustakaan Digital
            </p>

        </div>


        {{-- =====================================================
             BODY REGISTER
        ====================================================== --}}

        <div class="register-popup-body">


            {{-- =================================================
                 FORM REGISTER
            ================================================== --}}

            <form
                method="POST"
                action="{{ route('register') }}"
                class="register-popup-form"
            >

                @csrf


                {{-- =================================================
                     PESAN BERHASIL
                ================================================== --}}

                @if(session('register_success'))

                    <div class="register-popup-success">

                        {{ session('register_success') }}

                    </div>

                @endif


                {{-- =================================================
                     ERROR VALIDASI
                ================================================== --}}

                @if ($errors->any())

                    <div
                        class="register-popup-success"
                        style="
                            border-color:#FCA5A5;
                            background:#FEF2F2;
                            color:#B91C1C;
                        "
                    >

                        @foreach ($errors->all() as $error)

                            <div>
                                {{ $error }}
                            </div>

                        @endforeach

                    </div>

                @endif


                {{-- =================================================
                     NAMA LENGKAP
                ================================================== --}}

                <div class="register-popup-field">

                    <label for="register-name">
                        Nama Lengkap
                    </label>

                    <div class="register-popup-input">

                        <span class="register-popup-input-icon">
                            👤
                        </span>

                        <input
                            id="register-name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Nama lengkap kamu"
                            autocomplete="name"
                            required
                        >

                    </div>

                </div>


                {{-- =================================================
                     NIS
                ================================================== --}}

                <div class="register-popup-field">

                    <label for="register-nis">
                        NIS (Nomor Induk Siswa)
                    </label>

                    <div class="register-popup-input">

                        <span class="register-popup-input-icon">
                            🪪
                        </span>

                        <input
                            id="register-nis"
                            type="text"
                            name="nis"
                            value="{{ old('nis') }}"
                            placeholder="Contoh: 2024001234"
                            required
                        >

                    </div>

                </div>


                {{-- =================================================
                     EMAIL
                ================================================== --}}

                <div class="register-popup-field">

                    <label for="register-email">
                        Alamat Email
                    </label>

                    <div class="register-popup-input">

                        <span class="register-popup-input-icon">
                            ✉
                        </span>

                        <input
                            id="register-email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="contoh@email.com"
                            autocomplete="email"
                            required
                        >

                    </div>

                </div>

{{-- =================================================
     NOMOR TELEPON
================================================== --}}
<div class="register-popup-field">

    <label for="register-no-telepon">
        Nomor Telepon / WhatsApp
    </label>

    <div class="register-popup-input">

        <span class="register-popup-input-icon">
            📱
        </span>

        <input
            id="register-no-telepon"
            type="text"
            name="no_telepon"
            value="{{ old('no_telepon') }}"
            placeholder="Contoh: 081234567890"
            autocomplete="tel"
            maxlength="15"
            required
        >

    </div>

</div>
               {{-- =================================================
     KELAS
================================================== --}}

<div class="register-popup-field">

    <label for="register-kelas">
        Kelas
    </label>

    <div class="register-popup-input">

        <span class="register-popup-input-icon">
            🏫
        </span>

        <input
            id="register-kelas"
            type="text"
            name="kelas"
            value="{{ old('kelas') }}"
            placeholder="Contoh: XI"
            required
        >

    </div>

</div>

                {{-- =================================================
                     PASSWORD
                ================================================== --}}

                <div class="register-popup-field">

                    <label for="register-password">
                        Kata Sandi
                    </label>

                    <div class="register-popup-input">

                        <span class="register-popup-input-icon">
                            🔒
                        </span>

                        <input
                            id="register-password"
                            type="password"
                            name="password"
                            placeholder="Min. 8 karakter"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="register-popup-password-toggle"
                            onclick="togglePassword('register-password', this)"
                            aria-label="Tampilkan kata sandi"
                        >
                            👁
                        </button>

                    </div>

                </div>


                {{-- =================================================
                     KONFIRMASI PASSWORD
                ================================================== --}}

                <div class="register-popup-field">

                    <label for="register-password-confirmation">
                        Konfirmasi Kata Sandi
                    </label>

                    <div class="register-popup-input">

                        <span class="register-popup-input-icon">
                            🔒
                        </span>

                        <input
                            id="register-password-confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi kata sandi"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="register-popup-password-toggle"
                            onclick="togglePassword(
                                'register-password-confirmation',
                                this
                            )"
                            aria-label="Tampilkan konfirmasi kata sandi"
                        >
                            👁
                        </button>

                    </div>

                </div>


                {{-- =================================================
                     BUTTON REGISTER
                ================================================== --}}

                <button
                    type="submit"
                    class="register-popup-submit"
                >
                    Daftar Sekarang 🚀
                </button>


                {{-- =================================================
                     LINK LOGIN
                ================================================== --}}

                <div class="register-popup-login-link">

                    <span>
                        Sudah punya akun?
                    </span>

                    <a
                        href="#"
                        id="backToLogin"
                    >
                        Masuk di sini
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>