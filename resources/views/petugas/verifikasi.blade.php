@extends('layouts.petugas')

@section('title','Verifikasi Anggota')

@section('content')

<!-- Header -->
<div class="welcome-box">

    <div>
        <h1>Verifikasi Anggota</h1>
        <p>
            Kelola pendaftaran siswa yang menunggu persetujuan Guru Perpustakaan.
        </p>
    </div>

    <div class="welcome-icon">
        👨‍🎓
    </div>

</div>


<!-- Card -->
<div class="table-card">

    <div class="card-header">

        <div>
            <h3>Daftar Anggota Menunggu Verifikasi</h3>
            <small>{{ $anggota->count() }} siswa ditemukan</small>
        </div>

    </div>


    <table>

        <thead>

            <tr>

                <th>No</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Email</th>
                <th>Status</th>
                <th width="170">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($anggota as $index => $user)

            <tr>

                <td>{{ $index + 1 }}</td>

                <td>

                    <strong>{{ $user->name }}</strong>

                </td>

                <td>{{ $user->nis }}</td>

                <td>{{ $user->kelas }}</td>

                <td>{{ $user->email }}</td>

                <td>

                    <span class="badge waiting">
                        {{ ucfirst($user->status) }}
                    </span>

                </td>

                <td>

                    <div style="display:flex;gap:8px">

                        <a href="#"
                           class="btn-approve">
                            <i class="fa-solid fa-check"></i>
                            Setujui
                        </a>

                        <a href="#"
                           class="btn-reject">
                            <i class="fa-solid fa-xmark"></i>
                            Tolak
                        </a>

                    </div>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="7" style="text-align:center;padding:40px">

                    <i class="fa-solid fa-circle-check"
                       style="font-size:45px;color:#10b981"></i>

                    <br><br>

                    <strong>Tidak ada siswa yang menunggu verifikasi.</strong>

                    <br>

                    <small>Semua pendaftaran sudah diproses.</small>

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection