<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\ActivityLog;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR BUKU
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $books = Book::latest()->get();

        return view('books.index', compact('books'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH BUKU
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('books.create');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNGSI MEMBUAT EKSEMPLAR
    |--------------------------------------------------------------------------
    */

    private function addCopies(Book $book, int $quantity, ?string $rak): void
    {
        if ($quantity <= 0) {
            return;
        }

        $nomorTerakhir = (int) BookCopy::where(
            'book_id',
            $book->id
        )->max('nomor_eksemplar');

        for ($i = 1; $i <= $quantity; $i++) {

            $nomor = $nomorTerakhir + $i;

            BookCopy::create([
                'book_id' => $book->id,
                'nomor_eksemplar' => $nomor,
                'kode_eksemplar' => 'BK-' . $book->id . '-' . $nomor,
                'rak' => $rak,
                'status' => 'tersedia',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN BUKU
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string'],
            'penulis' => ['required', 'string'],
            'penerbit' => ['required', 'string'],
            'tahun' => ['required'],
            'kategori' => ['required', 'array', 'min:1'],
            'kategori.*' => ['required', 'string', 'max:100'],
            'jenis_buku' => ['required', 'in:literasi,paket'],
            'semester' => ['nullable', 'integer', 'in:1,2'],
            'rak' => ['nullable', 'string'],
            'stok' => ['required', 'integer', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'jenis_buku.in' => 'Jenis buku hanya boleh literasi atau paket.',
            'semester.integer' => 'Semester harus berupa angka.',
            'semester.in' => 'Semester hanya boleh 1 atau 2.',
            'stok.min' => 'Stok buku minimal 1.',
            'kategori.required' => 'Kategori buku wajib diisi.',
            'kategori.array' => 'Kategori buku tidak valid.',
            'kategori.min' => 'Minimal satu kategori harus diisi.',
            'kategori.*.required' => 'Kategori tidak boleh kosong.',
            'kategori.*.string' => 'Kategori harus berupa teks.',
            'kategori.*.max' => 'Kategori maksimal 100 karakter.',
        ]);

        $kategori = collect($validated['kategori'])
            ->map(fn($item) => trim($item))
            ->filter(fn($item) => $item !== '')
            ->unique(fn($item) => strtolower($item))
            ->implode(', ');

        if (
            $validated['jenis_buku'] === 'paket' &&
            empty($validated['semester'])
        ) {
            return back()
                ->withInput()
                ->with('error', 'Buku paket wajib memiliki semester.');
        }

        $semester = $validated['jenis_buku'] === 'paket'
            ? (int) $validated['semester']
            : null;

        $cover = null;

        if ($request->hasFile('cover')) {
            $cover = $request->file('cover')->store('books', 'public');
        }

        try {

            DB::transaction(function () use (
                $validated,
                $kategori,
                $semester,
                $cover,
                &$book
            ) {

                $book = Book::create([
                    'judul' => $validated['judul'],
                    'penulis' => $validated['penulis'],
                    'penerbit' => $validated['penerbit'],
                    'tahun' => $validated['tahun'],
                    'kategori' => $kategori,
                    'jenis_buku' => $validated['jenis_buku'],
                    'semester' => $semester,
                    'rak' => $validated['rak'] ?? null,
                    'stok' => (int) $validated['stok'],
                    'jumlah_dipinjam' => 0,
                    'deskripsi' => $validated['deskripsi'] ?? null,
                    'cover' => $cover,
                ]);

                $this->addCopies(
                    $book,
                    (int) $validated['stok'],
                    $validated['rak'] ?? null
                );

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'Tambah Buku',
                    'keterangan' => 'Menambahkan buku "' .
                        $book->judul .
                        '"' .
                        (
                            $book->jenis_buku === 'paket'
                                ? ' (Paket Semester ' . $book->semester . ')'
                                : ' (Literasi)'
                        ),
                ]);
            });

        } catch (\Throwable $e) {

            if ($cover) {
                Storage::disk('public')->delete($cover);
            }

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage());
        }

        return redirect()
            ->route('petugas.books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT BUKU
    |--------------------------------------------------------------------------
    */

    public function edit(Book $book)
    {
        $book->load('copies');

        return view('books.edit', compact('book'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE BUKU
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string'],
            'penulis' => ['required', 'string'],
            'penerbit' => ['required', 'string'],
            'tahun' => ['required'],
            'kategori' => ['required', 'array', 'min:1'],
            'kategori.*' => ['required', 'string', 'max:100'],
            'jenis_buku' => ['required', 'in:literasi,paket'],
            'semester' => ['nullable', 'integer', 'in:1,2'],
            'rak' => ['nullable', 'string'],
            'stok' => ['required', 'integer', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'jenis_buku.in' => 'Jenis buku hanya boleh literasi atau paket.',
            'semester.integer' => 'Semester harus berupa angka.',
            'semester.in' => 'Semester hanya boleh 1 atau 2.',
            'stok.min' => 'Stok buku minimal 1.',
            'kategori.required' => 'Kategori buku wajib diisi.',
            'kategori.array' => 'Kategori buku tidak valid.',
            'kategori.min' => 'Minimal satu kategori harus diisi.',
            'kategori.*.required' => 'Kategori tidak boleh kosong.',
            'kategori.*.string' => 'Kategori harus berupa teks.',
            'kategori.*.max' => 'Kategori maksimal 100 karakter.',
        ]);

        $kategori = collect($validated['kategori'])
            ->map(fn($item) => trim($item))
            ->filter(fn($item) => $item !== '')
            ->unique(fn($item) => strtolower($item))
            ->implode(', ');

        if ($kategori === '') {
            return back()
                ->withInput()
                ->with('error', 'Minimal satu kategori buku harus diisi.');
        }

        $statusAktif = [
            'menunggu',
            'dipinjam',
            'menunggu_pengembalian',
        ];

        $adaPeminjamanAktif = Borrowing::where('book_id', $book->id)
            ->whereIn('status', $statusAktif)
            ->exists();

        if ($adaPeminjamanAktif) {

            if ($validated['jenis_buku'] !== $book->jenis_buku) {
                return back()
                    ->withInput()
                    ->with('error', 'Jenis buku tidak dapat diubah karena masih memiliki peminjaman aktif.');
            }

            $semesterRequest = $validated['jenis_buku'] === 'paket'
                ? (int) $validated['semester']
                : null;

            $semesterLama = $book->semester !== null
                ? (int) $book->semester
                : null;

            if ($semesterRequest !== $semesterLama) {
                return back()
                    ->withInput()
                    ->with('error', 'Semester buku tidak dapat diubah karena masih memiliki peminjaman aktif.');
            }
        }

        if (
            $validated['jenis_buku'] === 'paket' &&
            empty($validated['semester'])
        ) {
            return back()
                ->withInput()
                ->with('error', 'Buku paket wajib memiliki semester.');
        }

        $semester = $validated['jenis_buku'] === 'paket'
            ? (int) $validated['semester']
            : null;

        $stokLama = (int) $book->stok;
        $stokBaru = (int) $validated['stok'];
        $selisih = $stokBaru - $stokLama;

        $judulLama = $book->judul;
        $jenisLama = $book->jenis_buku;
        $semesterLama = $book->semester;
        $coverLama = $book->cover;
        $coverBaru = $coverLama;

        if ($request->hasFile('cover')) {
            $coverBaru = $request->file('cover')->store('books', 'public');
        }

        try {

            DB::transaction(function () use (
                $book,
                $validated,
                $kategori,
                $semester,
                $stokBaru,
                $selisih,
                $coverBaru,
                $judulLama,
                $jenisLama,
                $semesterLama
            ) {

                if ($selisih < 0) {

                    $jumlahDikurangi = abs($selisih);

                    $copiesToRemove = $book->copies()
                        ->where('status', 'tersedia')
                        ->whereDoesntHave('borrowings')
                        ->orderByDesc('nomor_eksemplar')
                        ->limit($jumlahDikurangi)
                        ->get();

                    if ($copiesToRemove->count() < $jumlahDikurangi) {
                        throw new \RuntimeException(
                            'Stok tidak dapat dikurangi karena sebagian eksemplar memiliki riwayat peminjaman atau sedang dipinjam.'
                        );
                    }

                    foreach ($copiesToRemove as $copy) {
                        $copy->delete();
                    }
                }

                if ($selisih > 0) {
                    $this->addCopies(
                        $book,
                        $selisih,
                        $validated['rak'] ?? null
                    );
                }

                $book->judul = $validated['judul'];
                $book->penulis = $validated['penulis'];
                $book->penerbit = $validated['penerbit'];
                $book->tahun = $validated['tahun'];
                $book->kategori = $kategori;
                $book->jenis_buku = $validated['jenis_buku'];
                $book->semester = $semester;
                $book->rak = $validated['rak'] ?? null;
                $book->stok = $stokBaru;
                $book->deskripsi = $validated['deskripsi'] ?? null;
                $book->cover = $coverBaru;
                $book->save();

                $keterangan = 'Mengubah data buku "' . $judulLama . '"';

                if ($jenisLama !== $book->jenis_buku) {
                    $keterangan .=
                        ' dari ' .
                        ($jenisLama === 'paket' ? 'buku paket' : 'buku literasi') .
                        ' menjadi ' .
                        ($book->jenis_buku === 'paket' ? 'buku paket' : 'buku literasi');
                }

                if ($book->jenis_buku === 'paket') {
                    if ($semesterLama != $book->semester) {
                        $keterangan .=
                            ' dari Semester ' .
                            ($semesterLama ?? '-') .
                            ' menjadi Semester ' .
                            $book->semester;
                    } else {
                        $keterangan .= ' Semester ' . $book->semester;
                    }
                }

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'Edit Buku',
                    'keterangan' => $keterangan,
                ]);
            });

        } catch (\Throwable $e) {

            if ($coverBaru && $coverBaru !== $coverLama) {
                Storage::disk('public')->delete($coverBaru);
            }

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        if ($coverLama && $coverBaru !== $coverLama) {
            Storage::disk('public')->delete($coverLama);
        }

        return redirect()
            ->route('petugas.books.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | UBAH STATUS EKSEMPLAR MENJADI RUSAK
    |--------------------------------------------------------------------------
    */

    public function markCopyDamaged(Book $book, BookCopy $copy)
    {
        try {

            DB::transaction(function () use ($book, $copy) {

                $copy = $book->copies()
                    ->whereKey($copy->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($copy->status !== 'tersedia') {
                    throw new \RuntimeException(
                        'Hanya eksemplar berstatus tersedia yang dapat ditandai rusak.'
                    );
                }

                $copy->status = 'rusak';
                $copy->save();

                /*
                 * Kurangi stok tersedia sebanyak satu.
                 * Eksemplar tetap disimpan di tabel book_copies.
                 */

                $book->decrement('stok');

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'Ubah Status Eksemplar',
                    'keterangan' =>
                        'Mengubah eksemplar ' .
                        ($copy->kode_eksemplar ?? $copy->nomor_eksemplar) .
                        ' pada buku "' .
                        $book->judul .
                        '" menjadi rusak.',
                ]);
            });

            return redirect()
                ->route('petugas.books.edit', $book->id)
                ->with('success', 'Status eksemplar berhasil diubah menjadi rusak.');

        } catch (\Throwable $e) {

            return redirect()
                ->route('petugas.books.edit', $book->id)
                ->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS BUKU
    |--------------------------------------------------------------------------
    */

    public function destroy(Book $book)
    {
        $statusAktif = [
            'menunggu',
            'dipinjam',
            'menunggu_pengembalian',
        ];

        $adaPeminjamanAktif = Borrowing::where('book_id', $book->id)
            ->whereIn('status', $statusAktif)
            ->exists();

        if ($adaPeminjamanAktif) {
            return back()
                ->with('error', 'Buku tidak dapat dihapus karena masih memiliki peminjaman aktif.');
        }

        $judul = $book->judul;

        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }

        try {

            DB::transaction(function () use ($book, $judul) {

                $book->copies()->delete();

                $book->delete();

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'Hapus Buku',
                    'keterangan' => 'Menghapus buku "' . $judul . '"',
                ]);
            });

        } catch (\Throwable $e) {

            return back()
                ->with('error', 'Buku gagal dihapus: ' . $e->getMessage());
        }

        return redirect()
            ->route('petugas.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | FORM IMPORT
    |--------------------------------------------------------------------------
    */

    public function importForm()
    {
        return view('books.import-buku');
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT BUKU EXCEL
    |--------------------------------------------------------------------------
    */

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ], [
            'file_excel.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_excel.file' => 'File yang dipilih tidak valid.',
            'file_excel.mimes' => 'File harus berupa Excel (.xlsx/.xls) atau CSV.',
            'file_excel.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        try {

            $file = $request->file('file_excel');

            if (!$file || !$file->isValid()) {
                return back()->with('error', 'File Excel tidak dapat dibaca.');
            }

            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();

            $rows = $worksheet->toArray(null, true, true, true);

            if (count($rows) < 2) {
                return back()->with('error', 'File Excel tidak memiliki data buku.');
            }

            $headerRow = array_shift($rows);
            $headers = [];

            foreach ($headerRow as $column => $header) {

                if ($header === null) {
                    continue;
                }

                $header = strtolower(trim((string) $header));
                $header = preg_replace('/\s+/', '_', $header);

                $headers[$column] = $header;
            }

            $requiredColumns = [
                'judul',
                'penulis',
                'jenis_buku',
            ];

            foreach ($requiredColumns as $required) {

                if (!in_array($required, $headers, true)) {
                    return back()->with(
                        'error',
                        'Kolom "' . $required . '" tidak ditemukan di file Excel.'
                    );
                }
            }

            $allowedColumns = [
                'judul',
                'penulis',
                'penerbit',
                'tahun',
                'kategori',
                'jenis_buku',
                'semester',
                'rak',
                'stok',
                'deskripsi',
            ];

            $berhasil = 0;
            $dilewati = 0;
            $barisError = [];

            foreach ($rows as $index => $row) {

                $nomorBaris = $index + 2;
                $data = [];

                foreach ($headers as $column => $header) {

                    if (!in_array($header, $allowedColumns, true)) {
                        continue;
                    }

                    $value = $row[$column] ?? null;

                    if (is_string($value)) {
                        $value = trim($value);
                    }

                    if ($value === '') {
                        $value = null;
                    }

                    $data[$header] = $value;
                }

                $adaIsi = false;

                foreach ($data as $value) {
                    if ($value !== null && $value !== '') {
                        $adaIsi = true;
                        break;
                    }
                }

                if (!$adaIsi) {
                    continue;
                }

                if (empty($data['judul'])) {
                    $dilewati++;
                    $barisError[] = 'Baris ' . $nomorBaris . ': judul kosong.';
                    continue;
                }

                if (empty($data['penulis'])) {
                    $dilewati++;
                    $barisError[] = 'Baris ' . $nomorBaris . ': penulis kosong.';
                    continue;
                }

                $jenisBuku = strtolower(trim($data['jenis_buku'] ?? ''));

                if (!in_array($jenisBuku, ['literasi', 'paket'], true)) {
                    $dilewati++;
                    $barisError[] = 'Baris ' . $nomorBaris . ': jenis_buku harus literasi atau paket.';
                    continue;
                }

                $semester = $data['semester'] ?? null;

                if ($jenisBuku === 'paket') {

                    if (
                        $semester === null ||
                        !is_numeric($semester) ||
                        !in_array((int) $semester, [1, 2], true)
                    ) {
                        $dilewati++;
                        $barisError[] = 'Baris ' . $nomorBaris . ': buku paket wajib memiliki semester 1 atau 2.';
                        continue;
                    }

                    $semester = (int) $semester;

                } else {
                    $semester = null;
                }

                $stok = $data['stok'] ?? 1;

                if (!is_numeric($stok) || (int) $stok < 1) {
                    $dilewati++;
                    $barisError[] = 'Baris ' . $nomorBaris . ': stok harus berupa angka minimal 1.';
                    continue;
                }

                $stok = (int) $stok;

                $tahun = $data['tahun'] ?? null;

                if ($tahun !== null && !is_numeric($tahun)) {
                    $dilewati++;
                    $barisError[] = 'Baris ' . $nomorBaris . ': tahun harus berupa angka.';
                    continue;
                }

                if ($tahun !== null) {
                    $tahun = (int) $tahun;
                }

                $penerbit = $data['penerbit'] ?? null;
                $kategori = $data['kategori'] ?? null;
                $rak = $data['rak'] ?? null;
                $deskripsi = $data['deskripsi'] ?? null;

                if ($kategori !== null) {
                    $kategori = collect(explode(',', $kategori))
                        ->map(fn($item) => trim($item))
                        ->filter(fn($item) => $item !== '')
                        ->unique(fn($item) => strtolower($item))
                        ->implode(', ');
                }

                try {

                    DB::transaction(function () use (
                        $data,
                        $penerbit,
                        $tahun,
                        $kategori,
                        $jenisBuku,
                        $semester,
                        $rak,
                        $stok,
                        $deskripsi
                    ) {

                        $book = Book::create([
                            'judul' => $data['judul'],
                            'penulis' => $data['penulis'],
                            'penerbit' => $penerbit,
                            'tahun' => $tahun,
                            'kategori' => $kategori,
                            'jenis_buku' => $jenisBuku,
                            'semester' => $semester,
                            'rak' => $rak,
                            'stok' => $stok,
                            'jumlah_dipinjam' => 0,
                            'deskripsi' => $deskripsi,
                            'cover' => null,
                        ]);

                        $this->addCopies($book, $stok, $rak);
                    });

                    $berhasil++;

                } catch (\Throwable $e) {

                    $dilewati++;
                    $barisError[] = 'Baris ' . $nomorBaris . ': ' . $e->getMessage();
                }
            }

            if ($berhasil === 0) {

                $pesan = 'Tidak ada data buku yang berhasil diimport.';

                if (count($barisError) > 0) {
                    $pesan .= ' ' . implode(' ', array_slice($barisError, 0, 5));
                }

                return back()->with('error', $pesan);
            }

            $keterangan = 'Mengimport ' . $berhasil . ' buku dari file Excel.';

            if ($dilewati > 0) {
                $keterangan .= ' ' . $dilewati . ' baris dilewati.';
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Import Buku',
                'keterangan' => $keterangan,
            ]);

            $message = $berhasil . ' buku berhasil diimport.';

            if ($dilewati > 0) {
                $message .= ' ' . $dilewati . ' baris dilewati karena data tidak sesuai.';

                if (count($barisError) > 0) {
                    $message .= ' Contoh: ' . implode(' ', array_slice($barisError, 0, 3));
                }
            }

            return redirect()
                ->route('petugas.books.index')
                ->with('success', $message);

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with('error', 'Gagal mengimport file Excel: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD TEMPLATE EXCEL
    |--------------------------------------------------------------------------
    */

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Buku');

        $headers = [
            'judul',
            'penulis',
            'penerbit',
            'tahun',
            'kategori',
            'jenis_buku',
            'semester',
            'rak',
            'stok',
            'deskripsi',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $dataContoh = [
            [
                'Laskar Pelangi',
                'Andrea Hirata',
                'Bentang Pustaka',
                2005,
                'Novel',
                'literasi',
                null,
                'A1',
                5,
                'Novel tentang perjuangan pendidikan.',
            ],
            [
                'Matematika Kelas X',
                'Nama Penulis',
                'Erlangga',
                2025,
                'Pelajaran',
                'paket',
                1,
                'B1',
                10,
                'Buku paket semester 1.',
            ],
            [
                'Bahasa Indonesia Kelas X',
                'Nama Penulis',
                'Yudhistira',
                2025,
                'Pelajaran',
                'paket',
                2,
                'B2',
                10,
                'Buku paket semester 2.',
            ],
        ];

        $sheet->fromArray($dataContoh, null, 'A2');

        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        $sheet->getStyle('A1:J1')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A1:J1')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('D9EAF7');

        $sheet->getStyle('A1:J' . (count($dataContoh) + 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle('D2:D1000')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('G2:G1000')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('I2:I1000')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:J1');

        $widths = [
            'A' => 30,
            'B' => 25,
            'C' => 25,
            'D' => 12,
            'E' => 20,
            'F' => 15,
            'G' => 12,
            'H' => 12,
            'I' => 10,
            'J' => 45,
        ];

        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $sheet->getStyle('A1:J1000')
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);

        $petunjuk = $spreadsheet->createSheet();
        $petunjuk->setTitle('Petunjuk');

        $petunjuk->fromArray([
            ['PETUNJUK IMPORT BUKU'],
            [''],
            ['Kolom', 'Keterangan', 'Wajib'],
            ['judul', 'Judul buku', 'Ya'],
            ['penulis', 'Nama penulis/pengarang', 'Ya'],
            ['penerbit', 'Nama penerbit', 'Tidak'],
            ['tahun', 'Tahun terbit, berupa angka', 'Tidak'],
            ['kategori', 'Kategori buku', 'Tidak'],
            ['jenis_buku', 'literasi atau paket', 'Ya'],
            ['semester', '1 atau 2. Wajib untuk buku paket, kosong untuk literasi', 'Kondisional'],
            ['rak', 'Kode/lokasi rak buku', 'Tidak'],
            ['stok', 'Jumlah buku tersedia, minimal 1', 'Ya'],
            ['deskripsi', 'Deskripsi atau sinopsis buku', 'Tidak'],
            [''],
            ['ATURAN'],
            ['1', 'jenis_buku hanya boleh literasi atau paket.'],
            ['2', 'Buku literasi tidak menggunakan semester.'],
            ['3', 'Buku paket wajib memiliki semester 1 atau 2.'],
            ['4', 'Stok minimal 1.'],
            ['5', 'jumlah_dipinjam tidak perlu dimasukkan.'],
            ['6', 'cover tidak perlu dimasukkan karena cover diupload melalui sistem.'],
        ], null, 'A1');

        $petunjuk->getStyle('A1:C3')->getFont()->setBold(true);
        $petunjuk->getStyle('A1:C1')->getFont()->setSize(14);

        $petunjuk->getStyle('A1:C1')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('D9EAF7');

        $petunjuk->getStyle('A3:C3')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('EAF3F8');

        $petunjuk->getStyle('A1:C21')
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $petunjuk->getStyle('A1:C21')
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);

        $petunjuk->getColumnDimension('A')->setWidth(22);
        $petunjuk->getColumnDimension('B')->setWidth(70);
        $petunjuk->getColumnDimension('C')->setWidth(18);

        $spreadsheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            'template-import-buku.xlsx',
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT DATA BUKU EXCEL
    |--------------------------------------------------------------------------
    */

    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Buku');

        $headers = [
            'ID',
            'Judul',
            'Penulis',
            'Penerbit',
            'Tahun',
            'Kategori',
            'Jenis Buku',
            'Semester',
            'Rak',
            'Stok',
            'Deskripsi',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $books = Book::latest()->get();

        $row = 2;

        foreach ($books as $book) {

            $sheet->fromArray([
                $book->id,
                $book->judul,
                $book->penulis,
                $book->penerbit,
                $book->tahun,
                $book->kategori,
                $book->jenis_buku,
                $book->semester,
                $book->rak,
                $book->stok,
                $book->deskripsi,
            ], null, 'A' . $row);

            $row++;
        }

        $sheet->getStyle('A1:K1')->getFont()->setBold(true);

        $sheet->getStyle('A1:K1')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A1:K1')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('D9EAF7');

        $sheet->getStyle('A1:K' . max($row - 1, 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle('A1:K' . max($row - 1, 1))
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);

        $sheet->setAutoFilter('A1:K1');

        $widths = [
            'A' => 8,
            'B' => 30,
            'C' => 25,
            'D' => 25,
            'E' => 12,
            'F' => 20,
            'G' => 15,
            'H' => 12,
            'I' => 12,
            'J' => 10,
            'K' => 45,
        ];

        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $sheet->freezePane('A2');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            'data-buku.xlsx',
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }
}
