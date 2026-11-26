@extends('layouts.petugas')

@section('title', 'Poster Perpustakaan')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/petugas/poster.css') }}">
@endsection


@section('content')

<div class="poster-management">


   {{-- HEADER --}}
<div class="poster-management-header">

    <div class="poster-management-title">
        <h1>🖼️ Pengumuman Perpustakaan</h1>
        <p>{{ $posters->count() }} pengumuman tersedia</p>
    </div>

    <div class="poster-header-actions">

        <button
            type="button"
            class="btn-back-poster"
            onclick="window.location.href='{{ route('petugas.books.index') }}'"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </button>

        <a
            href="{{ route('petugas.posters.create') }}"
            class="btn-add-poster"
        >
            <i class="fa-solid fa-bullhorn"></i>
            Tambah Pengumuman
        </a>

<a
    href="{{ route('petugas.posters.createpromosi') }}"
    class="btn-add-poster"
>
    <i class="fa-solid fa-book-open"></i>
    Tambah Promosi
</a>
    </div>
</div>


    {{-- SUCCESS --}}

    @if(session('success'))

        <div class="poster-alert poster-alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- SLIDER --}}

    <div class="poster-admin-slider">

        @if($posters->count())

            <div
                class="poster-admin-track"
                id="posterAdminTrack"
            >

                @foreach($posters as $poster)

                    <article class="poster-admin-card">

                        <div class="poster-admin-image">

                            <img
                                src="{{ asset('storage/' . $poster->gambar) }}"
                                alt="{{ $poster->judul }}"
                            >

                        </div>


                        <div class="poster-admin-info">

                            <h3>
                                {{ $poster->judul }}
                            </h3>

                            <p>
                                {{ Str::limit($poster->deskripsi, 90) }}
                            </p>


                            @if($poster->status)

                                <span class="poster-status poster-active">
                                    Aktif
                                </span>

                            @else

                                <span class="poster-status poster-inactive">
                                    Nonaktif
                                </span>

                            @endif


                            <div class="poster-admin-actions">

                                <a
                                    href="{{ route('petugas.posters.edit', $poster->id) }}"
                                    class="poster-edit"
                                >

                                    <i class="fa-solid fa-pen"></i>

                                    Edit

                                </a>


                                <form
                                    action="{{ route('petugas.posters.destroy', $poster->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus poster ini?')"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="poster-delete"
                                    >

                                        <i class="fa-solid fa-trash"></i>

                                        Hapus

                                    </button>

                                </form>

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>


            <div class="poster-slider-buttons">

                <button
                    type="button"
                    class="poster-slider-btn"
                    id="posterPrev"
                >
                    ←
                </button>

                <button
                    type="button"
                    class="poster-slider-btn"
                    id="posterNext"
                >
                    →
                </button>

            </div>

        @else

            <div class="poster-empty">

                <div class="poster-empty-icon">
                    🖼️
                </div>

                <h3>
                    Belum ada Pengumuman
                </h3>

                <p>
                    Tambahkan Pengumuman pertama untuk ditampilkan kepada siswa.
                </p>

            </div>

        @endif

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const track =
            document.getElementById(
                'posterAdminTrack'
            );

        const prev =
            document.getElementById(
                'posterPrev'
            );

        const next =
            document.getElementById(
                'posterNext'
            );


        if (!track) {
            return;
        }


        function scrollPoster(direction) {

            track.scrollBy({

                left:
                    direction *
                    Math.min(
                        track.clientWidth * 0.8,
                        500
                    ),

                behavior: 'smooth'

            });

        }


        if (prev) {

            prev.addEventListener(
                'click',
                function () {

                    scrollPoster(-1);

                }
            );

        }


        if (next) {

            next.addEventListener(
                'click',
                function () {

                    scrollPoster(1);

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DRAG MOUSE
        |--------------------------------------------------------------------------
        */

        let isDown = false;
        let startX;
        let scrollLeft;


        track.addEventListener(
            'mousedown',
            function (event) {

                isDown = true;

                track.style.cursor =
                    'grabbing';

                startX =
                    event.pageX -
                    track.offsetLeft;

                scrollLeft =
                    track.scrollLeft;

            }
        );


        track.addEventListener(
            'mouseleave',
            function () {

                isDown = false;

                track.style.cursor =
                    'grab';

            }
        );


        track.addEventListener(
            'mouseup',
            function () {

                isDown = false;

                track.style.cursor =
                    'grab';

            }
        );


        track.addEventListener(
            'mousemove',
            function (event) {

                if (!isDown) {
                    return;
                }

                event.preventDefault();


                const x =
                    event.pageX -
                    track.offsetLeft;


                const walk =
                    (x - startX) * 1.5;


                track.scrollLeft =
                    scrollLeft - walk;

            }
        );

    }
);

</script>

@endsection