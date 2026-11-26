@extends('layouts.petugas')

@section('title','Hasil Pencarian')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/petugas/hasil-pencarian.css') }}">
@endpush

@section('content')

<div class="search-result-page">

    <div class="search-result-card">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="search-result-header">

            <div class="search-result-header-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>

            <h2>
                Hasil Pencarian
            </h2>

        </div>


        {{-- =====================================================
             KEYWORD
        ====================================================== --}}

        <div class="search-keyword">

            <span>
                Kata kunci:
            </span>

            <strong>
                "{{ $search }}"
            </strong>

        </div>


        {{-- =====================================================
             HASIL ANGGOTA
        ====================================================== --}}

        <section class="search-result-section">

            <h3 class="search-result-section-title">

                <i class="fa-solid fa-users"></i>

                Anggota

            </h3>


            @if($users->count())

                <div class="search-users-table-wrapper">

                    <table class="search-users-table">

                        <thead>

                            <tr>

                                <th>Nama</th>
                                <th>NIS</th>
                                <th>Email</th>
                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($users as $user)

                                <tr>

                                    <td class="search-user-name">
                                        {{ $user->name }}
                                    </td>

                                    <td>
                                        {{ $user->nis ?? '-' }}
                                    </td>

                                    <td class="search-user-email">
                                        {{ $user->email }}
                                    </td>

                                    <td>

                                        <span class="search-user-status">

                                            {{ ucfirst($user->status) }}

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="search-empty">

                    <i class="fa-solid fa-user-slash"></i>

                    Tidak ada anggota yang ditemukan.

                </div>

            @endif

        </section>


        {{-- =====================================================
             DIVIDER
        ====================================================== --}}

        <hr class="search-result-divider">


        {{-- =====================================================
             HASIL BUKU
        ====================================================== --}}

        <section class="search-result-section">

            <h3 class="search-result-section-title">

                <i class="fa-solid fa-book"></i>

                Buku

            </h3>


            @if($books->count())

                <div class="search-book-grid">

                    @foreach($books as $book)

                        <div class="search-book-card">

                            @if($book->cover)

                                <img
                                    src="{{ asset('storage/'.$book->cover) }}"
                                    alt="{{ $book->judul }}"
                                    class="search-book-cover"
                                >

                            @else

                                <div class="search-book-cover-placeholder">

                                    <i class="fa-solid fa-book"></i>

                                </div>

                            @endif


                            <div class="search-book-info">

                                <h4 class="search-book-title">
                                    {{ $book->judul }}
                                </h4>


                                <span class="search-book-author">

                                    <i class="fa-solid fa-pen-nib"></i>

                                    {{ $book->penulis ?? 'Penulis tidak diketahui' }}

                                </span>


                                @if($book->kategori)

                                    <div class="search-book-category">

                                        <i class="fa-solid fa-tag"
                                           style="margin-right:6px;">
                                        </i>

                                        {{ $book->kategori }}

                                    </div>

                                @endif


                                <div class="search-book-stock">

                                    <span>
                                        Stok Buku
                                    </span>

                                    <strong>
                                        {{ $book->stok }}
                                    </strong>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="search-empty">

                    <i class="fa-solid fa-book-open"></i>

                    Tidak ada buku yang ditemukan.

                </div>

            @endif

        </section>

    </div>

</div>

@endsection