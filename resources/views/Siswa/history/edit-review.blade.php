@extends('layouts.siswa')

@section('title', 'Edit Ulasan')

@section('content')

<div class="review-edit-page">

    <div class="review-edit-card">

        <div class="review-header">

            <h2>
                Edit Ulasan
            </h2>

            @if($review->book)

                <p>
                    {{ $review->book->judul }}
                </p>

            @endif

        </div>

        <form
            action="{{ route('siswa.review.update', $review->id) }}"
            method="POST"
        >

            @csrf

            @method('PUT')

            {{-- =====================================================
                RATING
            ====================================================== --}}

            <div class="form-group">

                <label for="rating">
                    Rating
                </label>

                <select
                    name="rating"
                    id="rating"
                    required
                >

                    <option value="">
                        Pilih Rating
                    </option>

                    @for($i = 1; $i <= 5; $i++)

                        <option
                            value="{{ $i }}"
                            {{ (int) $review->rating === $i ? 'selected' : '' }}
                        >
                            {{ $i }} ⭐
                        </option>

                    @endfor

                </select>

                @error('rating')

                    <small class="error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            {{-- =====================================================
                ULASAN
            ====================================================== --}}

            <div class="form-group">

                <label for="ulasan">
                    Ulasan
                </label>

                <textarea
                    name="ulasan"
                    id="ulasan"
                    rows="6"
                    maxlength="1000"
                    placeholder="Tulis ulasan buku..."
                >{{ old('ulasan', $review->ulasan) }}</textarea>

                @error('ulasan')

                    <small class="error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            {{-- =====================================================
                TOMBOL
            ====================================================== --}}

            <div class="review-buttons">

                <a
                    href="{{ route('siswa.history') }}"
                    class="btn-cancel"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-save"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


<style>

.review-edit-page {
    width: 100%;
    max-width: 700px;
    margin: 30px auto;
    padding: 20px;
}

.review-edit-card {
    background: #ffffff;
    border: 1px solid #fed7aa;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.review-header {
    margin-bottom: 25px;
}

.review-header h2 {
    margin: 0;
    color: #ea580c;
    font-size: 24px;
    font-weight: 800;
}

.review-header p {
    margin-top: 6px;
    color: #6b7280;
    font-size: 14px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #374151;
    font-size: 14px;
    font-weight: 700;
}

.form-group select,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #d1d5db;
    border-radius: 9px;
    padding: 10px 12px;
    font-family: inherit;
    font-size: 14px;
    background: #ffffff;
}

.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #f97316;
}

.form-group textarea {
    resize: vertical;
    min-height: 130px;
}

.error {
    display: block;
    margin-top: 5px;
    color: #dc2626;
    font-size: 12px;
}

.review-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 25px;
}

.btn-cancel,
.btn-save {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 38px;
    padding: 0 16px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
}

.btn-cancel {
    border: 1px solid #d1d5db;
    background: #ffffff;
    color: #374151;
}

.btn-save {
    border: 1px solid #f97316;
    background: #f97316;
    color: #ffffff;
}

.btn-save:hover {
    background: #ea580c;
}

</style>

@endsection