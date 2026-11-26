<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reset Kata Sandi - Perpustakaan Digital</title>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fffaf4;
        font-family: "Poppins", Arial, sans-serif;
        color: #3d3028;
    }

    .reset-container {
        width: 100%;
        max-width: 430px;
        padding: 20px;
    }

    .reset-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 38px 32px;
        box-shadow: 0 12px 40px rgba(90, 65, 45, 0.10);
        border: 1px solid #f1e7dc;
    }

    .reset-header {
        text-align: center;
        margin-bottom: 28px;
    }

    .reset-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 16px;
        border-radius: 20px;
        background: #fff1df;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
    }

    .reset-header h1 {
        margin: 0 0 8px;
        font-size: 25px;
        font-weight: 700;
    }

    .reset-header p {
        margin: 0;
        font-size: 13px;
        line-height: 1.7;
        color: #8a7b70;
    }

    .error-message {
        margin-bottom: 18px;
        padding: 12px 14px;
        border-radius: 12px;
        background: #fff0f0;
        color: #b23a3a;
        font-size: 13px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .input-wrap {
        position: relative;
    }

    .form-input {
        width: 100%;
        height: 50px;
        padding: 0 45px 0 15px;
        border: 1px solid #e5d8cc;
        border-radius: 13px;
        outline: none;
        font-size: 14px;
        color: #3d3028;
        background: #fffdfb;
        transition: 0.2s;
    }

    .form-input:focus {
        border-color: #d98b45;
        box-shadow: 0 0 0 3px rgba(217, 139, 69, 0.10);
    }

    .toggle-password {
        position: absolute;
        right: 13px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        cursor: pointer;
        font-size: 18px;
        padding: 3px;
    }

    .submit-button {
        width: 100%;
        height: 50px;
        margin-top: 8px;
        border: none;
        border-radius: 13px;
        background: #d98b45;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }

    .submit-button:hover {
        background: #c97935;
    }

    .back-login {
        display: block;
        margin-top: 20px;
        text-align: center;
        text-decoration: none;
        color: #8a5a32;
        font-size: 13px;
        font-weight: 600;
    }

    .back-login:hover {
        text-decoration: underline;
    }

    .password-info {
        margin-top: 18px;
        padding: 12px 14px;
        border-radius: 12px;
        background: #f8f4ef;
        color: #75675d;
        font-size: 12px;
        line-height: 1.6;
    }

    @media (max-width: 480px) {
        .reset-card {
            padding: 30px 22px;
        }
    }
</style>


</head>

<body>

<div class="reset-container">


<div class="reset-card">

    {{-- HEADER --}}
    <div class="reset-header">

        <div class="reset-icon">
            🔐
        </div>

        <h1>
            Buat Kata Sandi Baru
        </h1>

        <p>
            Silakan masukkan kata sandi baru untuk
            akun Perpustakaan Digital Anda.
        </p>

    </div>


    {{-- ERROR --}}
    @if ($errors->any())

        <div class="error-message">

            @foreach ($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif


    {{-- FORM RESET --}}
    <form
        method="POST"
        action="{{ route('password.store') }}"
    >

        @csrf

        {{-- TOKEN --}}
        <input
            type="hidden"
            name="token"
            value="{{ $request->route('token') }}"
        >


        {{-- EMAIL --}}
        <div class="form-group">

            <label for="email">
                Alamat Email
            </label>

            <input
                id="email"
                type="email"
                name="email"
                class="form-input"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="email"
                placeholder="contoh@email.com"
            >

        </div>


        {{-- PASSWORD BARU --}}
        <div class="form-group">

            <label for="password">
                Kata Sandi Baru
            </label>

            <div class="input-wrap">

                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-input"
                    required
                    autocomplete="new-password"
                    placeholder="Masukkan kata sandi baru"
                >

                <button
                    type="button"
                    class="toggle-password"
                    onclick="togglePassword('password', this)"
                    aria-label="Tampilkan kata sandi"
                >
                    👁
                </button>

            </div>

        </div>


        {{-- KONFIRMASI PASSWORD --}}
        <div class="form-group">

            <label for="password_confirmation">
                Konfirmasi Kata Sandi
            </label>

            <div class="input-wrap">

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-input"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi kata sandi baru"
                >

                <button
                    type="button"
                    class="toggle-password"
                    onclick="togglePassword('password_confirmation', this)"
                    aria-label="Tampilkan kata sandi"
                >
                    👁
                </button>

            </div>

        </div>


        {{-- SUBMIT --}}
        <button
            type="submit"
            class="submit-button"
        >
            Simpan Kata Sandi Baru
        </button>

    </form>


    {{-- KEMBALI --}}
    <a
        href="{{ url('/') }}"
        class="back-login"
    >
        ← Kembali ke Login
    </a>


    {{-- INFO --}}
    <div class="password-info">
        🔒 Gunakan kata sandi yang mudah Anda ingat
        tetapi sulit ditebak oleh orang lain.
    </div>

</div>


</div>

<script>

function togglePassword(id, button) {

    const input = document.getElementById(id);

    if (input.type === "password") {

        input.type = "text";
        button.textContent = "🙈";

    } else {

        input.type = "password";
        button.textContent = "👁";

    }

}

</script>

</body>
</html>
