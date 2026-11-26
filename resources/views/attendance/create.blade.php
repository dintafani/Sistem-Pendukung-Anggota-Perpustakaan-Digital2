<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Hadir - Perpustakaan</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 15px;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #f8f5ef;
        }

        .attendance-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 28px;
            color: #333;
        }

        .header p {
            margin: 0;
            color: #777;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        label span {
            color: #e74c3c;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
            outline: none;
            background: white;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #f39c12;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .error {
            margin-top: 6px;
            color: #e74c3c;
            font-size: 13px;
        }

        .success {
            margin-bottom: 20px;
            padding: 13px 15px;
            border-radius: 10px;
            background: #eaf8ee;
            color: #287a3e;
            font-size: 14px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            flex: 1;
            padding: 13px 20px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .btn-submit {
            background: #f39c12;
            color: white;
        }

        .btn-submit:hover {
            background: #e67e22;
        }

        .btn-back {
            background: #eee;
            color: #555;
        }

        @media (max-width: 600px) {
            .attendance-container {
                padding: 25px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="attendance-container">

    <div class="header">
        <h1>Daftar Hadir</h1>
        <p>Silakan isi daftar hadir sebelum menggunakan fasilitas perpustakaan.</p>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf

        {{-- Nama --}}
        <div class="form-group">
            <label for="nama">
                Nama Lengkap <span>*</span>
            </label>

            <input
                type="text"
                id="nama"
                name="nama"
                value="{{ old('nama') }}"
                placeholder="Masukkan nama lengkap"
                required
            >

            @error('nama')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Instansi --}}
        <div class="form-group">
            <label for="instansi">
                Asal Sekolah / Instansi
            </label>

            <input
                type="text"
                id="instansi"
                name="instansi"
                value="{{ old('instansi') }}"
                placeholder="Contoh: SMA Negeri 03 Kotabumi"
            >

            @error('instansi')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Jenis Pengunjung --}}
        <div class="form-group">
            <label for="jenis_pengunjung">
                Jenis Pengunjung <span>*</span>
            </label>

            <select
                id="jenis_pengunjung"
                name="jenis_pengunjung"
                required
            >
                <option value="">-- Pilih Jenis Pengunjung --</option>

                <option value="Siswa"
                    {{ old('jenis_pengunjung') == 'Siswa' ? 'selected' : '' }}>
                    Siswa
                </option>

                <option value="Guru"
                    {{ old('jenis_pengunjung') == 'Guru' ? 'selected' : '' }}>
                    Guru
                </option>

                <option value="Petugas"
                    {{ old('jenis_pengunjung') == 'Petugas' ? 'selected' : '' }}>
                    Petugas
                </option>

                <option value="Anak Magang"
                    {{ old('jenis_pengunjung') == 'Magang/PKL' ? 'selected' : '' }}>
                     Magang/PKL
                </option>

                <option value="Tamu/Kunjungan"
                    {{ old('jenis_pengunjung') == 'Tamu/Kunjungan' ? 'selected' : '' }}>
                    Tamu / Kunjungan
                </option>

                <option value="Lainnya"
                    {{ old('jenis_pengunjung') == 'Lainnya' ? 'selected' : '' }}>
                    Lainnya
                </option>
            </select>

            @error('jenis_pengunjung')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kelas --}}
        <div class="form-group">
            <label for="kelas">
                Kelas
            </label>

            <input
                type="text"
                id="kelas"
                name="kelas"
                value="{{ old('kelas') }}"
                placeholder="Contoh: XI IPA 2 (khusus siswa)"
            >

            @error('kelas')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tujuan --}}
        <div class="form-group">
            <label for="tujuan">
                Tujuan Berkunjung
            </label>

            <textarea
                id="tujuan"
                name="tujuan"
                placeholder="Contoh: Membaca buku, meminjam buku, kegiatan magang, kunjungan, dll."
            >{{ old('tujuan') }}</textarea>

            @error('tujuan')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="button-group">

            <a href="{{ url('/') }}" class="btn btn-back">
                Kembali
            </a>

            <button type="submit" class="btn btn-submit">
                Isi Daftar Hadir
            </button>

        </div>

    </form>

</div>

</body>
</html>