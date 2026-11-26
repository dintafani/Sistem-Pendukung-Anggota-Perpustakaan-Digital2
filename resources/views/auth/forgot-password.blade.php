<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Lupa Kata Sandi - Perpustakaan Digital</title>

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

    .forgot-container {
        width: 100%;
        max-width: 430px;
        padding: 20px;
    }

    .forgot-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 38px 32px;
        box-shadow: 0 12px 40px rgba(90, 65, 45, 0.10);
        border: 1px solid #f1e7dc;
    }

    .forgot-header {
        text-align: center;
        margin-bottom: 28px;
    }

    .forgot-icon {
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

    .forgot-header h1 {
        margin: 0 0 8px;
        font-size: 25px;
        font-weight: 700;
    }

    .forgot-header p {
        margin: 0;
        font-size: 13px;
        line-height: 1.7;
        color: #8a7b70;
    }

    .status-message {
        margin-bottom: 18px;
        padding: 12px 14px;
        border-radius: 12px;
        background: #edf8f0;
        color: #26733b;
        font-size: 13px;
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
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .form-input {
        width: 100%;
        height: 50px;
        padding: 0 15px;
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

    .submit-button {
        width: 100%;
        height: 50px;
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

    .whatsapp-info {
        margin-top: 18px;
        padding: 12px 14px;
        border-radius: 12px;
        background: #f8f4ef;
        color: #75675d;
        font-size: 12px;
        line-height: 1.6;
        text-align: center;
    }

    @media (max-width: 480px) {
        .forgot-card {
            padding: 30px 22px;
        }
    }
</style>


</head>

<body>

<div class="forgot-container">

<div class="forgot-card">

    {{-- HEADER --}}
    <div class="forgot-header">

        <div class="forgot-icon">
            📚
        </div>

        <h1>
            Lupa Kata Sandi?
        </h1>

        <p>
            Jangan khawatir. Masukkan email yang terdaftar
            pada akun Anda untuk mendapatkan link reset
            kata sandi melalui WhatsApp.
        </p>

    </div>


    {{-- PESAN BERHASIL --}}
    @if (session('status'))

        <div class="status-message">
            {{ session('status') }}
        </div>

    @endif


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


    {{-- FORM --}}
    <form
        method="POST"
        action="{{ route('password.email') }}"
    >

        @csrf

        <div class="form-group">

            <label for="email">
                Alamat Email
            </label>

            <input
                id="email"
                type="email"
                name="email"
                class="form-input"
                value="{{ old('email') }}"
                placeholder="contoh@email.com"
                autocomplete="email"
                required
                autofocus
            >

        </div>


        <button
            type="submit"
            class="submit-button"
        >
            Kirim Link Reset
        </button>

    </form>


    {{-- KEMBALI LOGIN --}}
    <a
        href="{{ url('/') }}"
        class="back-login"
    >
        ← Kembali ke Login
    </a>


    {{-- INFO --}}
    <div class="whatsapp-info">
        🔐 Link pengaturan ulang kata sandi akan
        dikirim ke nomor WhatsApp yang terdaftar
        pada akun Anda.
    </div>

</div>

</div>

</body>
</html>
