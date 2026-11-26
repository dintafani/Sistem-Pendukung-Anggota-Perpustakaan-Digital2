{{-- =========================================================
     POPUP LOGIN
========================================================= --}}

<div
    id="loginModal"
    class="login-modal"
    aria-hidden="true"
>

    <div
        class="login-popup"
        role="dialog"
        aria-modal="true"
        aria-labelledby="loginTitle"
    >

        {{-- =====================================================
             HEADER LOGIN
        ====================================================== --}}

        <div class="login-popup-header">

            <div class="login-header-inner">

                {{-- ICON --}}
                <div class="login-icon">
                    📚
                </div>

                {{-- TITLE --}}
                <h2 id="loginTitle">
                    Selamat Datang!
                </h2>

                {{-- SUBTITLE --}}
                <p>
                    Masuk ke Perpustakaan Digital
                </p>

            </div>


            {{-- =================================================
                 CLOSE BUTTON
            ================================================== --}}

            <button
                type="button"
                class="login-close"
                id="closeLogin"
                aria-label="Tutup"
            >
                ✕
            </button>

        </div>


        {{-- =====================================================
             BODY LOGIN
        ====================================================== --}}

        <div class="login-popup-body">

            {{-- =================================================
                 FORM LOGIN
            ================================================== --}}

            <form
                method="POST"
                action="{{ route('login') }}"
                style="width: 100%; display: flex; flex-direction: column; gap: 16px;"
            >

                @csrf


                {{-- =============================================
                     ERROR LOGIN
                ============================================== --}}

                @if ($errors->any())

                    <div class="login-error">

                        @foreach ($errors->all() as $error)

                            <div>
                                {{ $error }}
                            </div>

                        @endforeach

                    </div>

                @endif


                {{-- =============================================
                     EMAIL
                ============================================== --}}

                <div class="login-field">

                    <label for="loginEmail">
                        Alamat Email
                    </label>

                    <div class="login-input-wrap">

                        {{-- ICON EMAIL --}}
                        <span class="login-input-icon">
                            ✉
                        </span>


                        {{-- INPUT EMAIL --}}
                        <input
                            id="loginEmail"
                            type="email"
                            name="email"
                            class="login-input"
                            value="{{ old('email') }}"
                            placeholder="contoh@email.com"
                            autocomplete="email"
                            required
                        >

                    </div>

                </div>


                {{-- =============================================
                     PASSWORD
                ============================================== --}}

                <div class="login-field">

                    <label for="loginPassword">
                        Kata Sandi
                    </label>

                    <div class="login-input-wrap">

                        {{-- ICON PASSWORD --}}
                        <span class="login-input-icon">
                            🔒
                        </span>


                        {{-- INPUT PASSWORD --}}
                        <input
                            id="loginPassword"
                            type="password"
                            name="password"
                            class="login-input"
                            placeholder="Masukkan kata sandi"
                            autocomplete="current-password"
                            required
                        >


                        {{-- TOGGLE PASSWORD --}}
                        <button
                            type="button"
                            class="login-password-toggle"
                            onclick="togglePassword('loginPassword', this)"
                            aria-label="Tampilkan kata sandi"
                        >
                            👁
                        </button>

                    </div>

                </div>


                {{-- =============================================
                     LUPA PASSWORD
                ============================================== --}}

                <div class="login-forgot">

    <a href="{{ route('password.request') }}">
        Lupa kata sandi?
    </a>

</div>
                


                {{-- =============================================
                     TOMBOL LOGIN
                ============================================== --}}

                <button
                    type="submit"
                    class="login-submit"
                >
                    Masuk Sekarang 
                </button>


                {{-- =============================================
                     LINK REGISTER
                ============================================== --}}

                <div class="login-register-link">

                    <span>
                        Belum punya akun?
                    </span>

                    <a
                        href="#"
                        id="loginToRegister"
                    >
                        Daftar di sini
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>