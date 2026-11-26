<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ActivityLog;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BookController extends Controller
{
    /*
    DAFTAR BUKU
    */

    public function index()
    {
        $books = Book::latest()->get();

        return view(
            'books.index',
            compact('books')
        );
    }


    /*
    FORM TAMBAH BUKU
    */

    public function create()
    {
        return view('books.create');
    }


    /*
    SIMPAN BUKU
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => [
                'required',
                'string',
            ],

            'penulis' => [
                'required',
                'string',
            ],

            'penerbit' => [
                'required',
                'string',
            ],

            'tahun' => [
                'required',
            ],

            'kategori' => [
                'required',
                'array',
                'min:1',
            ],

            'kategori.*' => [
                'required',
                'string',
                'max:100',
            ],

            'jenis_buku' => [
                'required',
                'in:literasi,paket',
            ],

            'semester' => [
                'nullable',
                'integer',
                'in:1,2',
            ],

            'rak' => [
                'nullable',
                'string',
            ],

            'stok' => [
                'required',
                'integer',
                'min:1',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'cover' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ], [
            'jenis_buku.in' =>
                'Jenis buku hanya boleh literasi atau paket.',

            'semester.integer' =>
                'Semester harus berupa angka.',

            'semester.in' =>
                'Semester hanya boleh 1 atau 2.',

            'stok.min' =>
                'Stok buku minimal 1.',

            'kategori.required' =>
                'Kategori buku wajib diisi.',

            'kategori.array' =>
                'Kategori buku tidak valid.',

            'kategori.min' =>
                'Minimal satu kategori harus diisi.',

            'kategori.*.required' =>
                'Kategori tidak boleh kosong.',

            'kategori.*.string' =>
                'Kategori harus berupa teks.',

            'kategori.*.max' =>
                'Kategori maksimal 100 karakter.',
        ]);


        /*
        GABUNGKAN MULTIPLE KATEGORI
        */

        $kategori = collect($validated['kategori'])
            ->map(function ($item) {
                return trim($item);
            })
            ->filter(function ($item) {
                return $item !== '';
            })
            ->unique(function ($item) {
                return strtolower($item);
            })
            ->implode(', ');


        /*
        VALIDASI JENIS BUKU
        */

        if ($validated['jenis_buku'] === 'paket') {

            if (
                !isset($validated['semester']) ||
                $validated['semester'] === null ||
                $validated['semester'] === ''
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Buku paket wajib memiliki semester.'
                    );
            }
        }


        /*
        TENTUKAN SEMESTER
        */

        $semester = null;

        if (
            $validated['jenis_buku'] === 'paket'
        ) {

            $semester =
                (int) $validated['semester'];
        }


        /*
        UPLOAD COVER
        */

        $cover = null;

        if ($request->hasFile('cover')) {

            $cover = $request
                ->file('cover')
                ->store(
                    'books',
                    'public'
                );
        }


        /*
        SIMPAN BUKU
        */

        $book = Book::create([

            'judul' =>
                $validated['judul'],

            'penulis' =>
                $validated['penulis'],

            'penerbit' =>
                $validated['penerbit'],

            'tahun' =>
                $validated['tahun'],

            'kategori' =>
                $kategori,

            'jenis_buku' =>
                $validated['jenis_buku'],

            'semester' =>
                $semester,

            'rak' =>
                $validated['rak'] ?? null,

            'stok' =>
                (int) $validated['stok'],

            'jumlah_dipinjam' =>
                0,

            'deskripsi' =>
                $validated['deskripsi'] ?? null,

            'cover' =>
                $cover,
        ]);


        /*
        ACTIVITY LOG
        */

        ActivityLog::create([

            'user_id' =>
                Auth::id(),

            'aktivitas' =>
                'Tambah Buku',

            'keterangan' =>
                'Menambahkan buku "' .
                $book->judul .
                '"' .
                (
                    $book->jenis_buku === 'paket'
                        ? ' (Paket Semester ' .
                          $book->semester .
                          ')'
                        : ' (Literasi)'
                ),
        ]);


        return redirect()
            ->route('petugas.books.index')
            ->with(
                'success',
                'Buku berhasil ditambahkan.'
            );
    }


    /*
    FORM EDIT BUKU
    */

    public function edit(Book $book)
    {
        return view(
            'books.edit',
            compact('book')
        );
    }


    /*
    UPDATE BUKU
    */

    public function update(
        Request $request,
        Book $book
    ) {

        $validated = $request->validate([
            'judul' => [
                'required',
                'string',
            ],

            'penulis' => [
                'required',
                'string',
            ],

            'penerbit' => [
                'required',
                'string',
            ],

            'tahun' => [
                'required',
            ],

            'kategori' => [
                'required',
                'array',
                'min:1',
            ],

            'kategori.*' => [
                'required',
                'string',
                'max:100',
            ],

            'jenis_buku' => [
                'required',
                'in:literasi,paket',
            ],

            'semester' => [
                'nullable',
                'integer',
                'in:1,2',
            ],

            'rak' => [
                'nullable',
                'string',
            ],

            'stok' => [
                'required',
                'integer',
                'min:1',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'cover' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ], [
            'jenis_buku.in' =>
                'Jenis buku hanya boleh literasi atau paket.',

            'semester.integer' =>
                'Semester harus berupa angka.',

            'semester.in' =>
                'Semester hanya boleh 1 atau 2.',

            'stok.min' =>
                'Stok buku minimal 1.',

            'kategori.required' =>
                'Kategori buku wajib diisi.',

            'kategori.array' =>
                'Kategori buku tidak valid.',

            'kategori.min' =>
                'Minimal satu kategori harus diisi.',

            'kategori.*.required' =>
                'Kategori tidak boleh kosong.',

            'kategori.*.string' =>
                'Kategori harus berupa teks.',

            'kategori.*.max' =>
                'Kategori maksimal 100 karakter.',
        ]);


        /*
        GABUNGKAN MULTIPLE KATEGORI
        */

        $kategori = collect($validated['kategori'])
            ->map(function ($item) {
                return trim($item);
            })
            ->filter(function ($item) {
                return $item !== '';
            })
            ->unique(function ($item) {
                return strtolower($item);
            })
            ->implode(', ');


        /*
        CEK PEMINJAMAN AKTIF
        */

        $statusAktif = [
            'menunggu',
            'dipinjam',
            'menunggu_pengembalian',
        ];


        $adaPeminjamanAktif = Borrowing::where(
            'book_id',
            $book->id
        )
            ->whereIn(
                'status',
                $statusAktif
            )
            ->exists();


        /*
        JIKA MASIH ADA PEMINJAMAN AKTIF
        */

        if ($adaPeminjamanAktif) {

            if (
                $validated['jenis_buku'] !==
                $book->jenis_buku
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Jenis buku tidak dapat diubah karena masih memiliki peminjaman aktif.'
                    );
            }


            $semesterRequest = null;

            if (
                $validated['jenis_buku'] === 'paket'
            ) {

                $semesterRequest =
                    (int) $validated['semester'];
            }


            $semesterLama =
                $book->semester !== null
                    ? (int) $book->semester
                    : null;


            if (
                $semesterRequest !==
                $semesterLama
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Semester buku tidak dapat diubah karena masih memiliki peminjaman aktif.'
                    );
            }
        }


        /*
        VALIDASI SEMESTER
        */

        if (
            $validated['jenis_buku'] === 'paket'
        ) {

            if (
                !isset($validated['semester']) ||
                $validated['semester'] === null ||
                $validated['semester'] === ''
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Buku paket wajib memiliki semester.'
                    );
            }
        }


        /*
        TENTUKAN SEMESTER
        */

        $semester = null;

        if (
            $validated['jenis_buku'] === 'paket'
        ) {

            $semester =
                (int) $validated['semester'];
        }


        /*
        CEK STOK
        */

        if (
            (int) $validated['stok'] < 1
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Stok buku minimal 1.'
                );
        }


        /*
        DATA LAMA
        */

        $judulLama =
            $book->judul;

        $jenisLama =
            $book->jenis_buku;

        $semesterLama =
            $book->semester;


        /*
        UPDATE COVER
        */

        if ($request->hasFile('cover')) {

            if ($book->cover) {

                Storage::disk('public')
                    ->delete(
                        $book->cover
                    );
            }


            $book->cover =
                $request
                    ->file('cover')
                    ->store(
                        'books',
                        'public'
                    );
        }


        /*
        UPDATE DATA BUKU
        */

        $book->judul =
            $validated['judul'];

        $book->penulis =
            $validated['penulis'];

        $book->penerbit =
            $validated['penerbit'];

        $book->tahun =
            $validated['tahun'];

        $book->kategori =
            $kategori;

        $book->jenis_buku =
            $validated['jenis_buku'];

        $book->semester =
            $semester;

        $book->rak =
            $validated['rak'] ?? null;

        $book->stok =
            (int) $validated['stok'];

        $book->deskripsi =
            $validated['deskripsi'] ?? null;

        $book->save();


        /*
        ACTIVITY LOG
        */

        $keterangan =
            'Mengubah data buku "' .
            $judulLama .
            '"';


        if (
            $jenisLama !==
            $book->jenis_buku
        ) {

            $keterangan .=
                ' dari ' .
                (
                    $jenisLama === 'paket'
                        ? 'buku paket'
                        : 'buku literasi'
                ) .
                ' menjadi ' .
                (
                    $book->jenis_buku === 'paket'
                        ? 'buku paket'
                        : 'buku literasi'
                );
        }


        if (
            $book->jenis_buku === 'paket'
        ) {

            if (
                $semesterLama !==
                $book->semester
            ) {

                $keterangan .=
                    ' dari Semester ' .
                    ($semesterLama ?? '-') .
                    ' menjadi Semester ' .
                    $book->semester;

            } else {

                $keterangan .=
                    ' Semester ' .
                    $book->semester;
            }
        }


        ActivityLog::create([

            'user_id' =>
                Auth::id(),

            'aktivitas' =>
                'Edit Buku',

            'keterangan' =>
                $keterangan,
        ]);


        return redirect()
            ->route(
                'petugas.books.index'
            )
            ->with(
                'success',
                'Data buku berhasil diperbarui.'
            );
    }


    /*
    HAPUS BUKU
    */

    public function destroy(Book $book)
    {
        $statusAktif = [
            'menunggu',
            'dipinjam',
            'menunggu_pengembalian',
        ];


        $adaPeminjamanAktif =
            Borrowing::where(
                'book_id',
                $book->id
            )
                ->whereIn(
                    'status',
                    $statusAktif
                )
                ->exists();


        if ($adaPeminjamanAktif) {

            return back()
                ->with(
                    'error',
                    'Buku tidak dapat dihapus karena masih memiliki peminjaman aktif.'
                );
        }


        $judul =
            $book->judul;


        if ($book->cover) {

            Storage::disk('public')
                ->delete(
                    $book->cover
                );
        }


        $book->delete();


        ActivityLog::create([

            'user_id' =>
                Auth::id(),

            'aktivitas' =>
                'Hapus Buku',

            'keterangan' =>
                'Menghapus buku "' .
                $judul .
                '"',
        ]);


        return redirect()
            ->route(
                'petugas.books.index'
            )
            ->with(
                'success',
                'Buku berhasil dihapus.'
            );
    }


    // ==========================================================
    // IMPORT BUKU EXCEL
    // ==========================================================

    public function importForm()
    {
        return view(
            'books.import-buku'
        );
    }


    public function import(
        Request $request
    ) {

        $request->validate([
            'file_excel' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:5120',
            ],
        ], [
            'file_excel.required' =>
                'Silakan pilih file Excel terlebih dahulu.',

            'file_excel.file' =>
                'File yang dipilih tidak valid.',

            'file_excel.mimes' =>
                'File harus berupa Excel (.xlsx/.xls) atau CSV.',

            'file_excel.max' =>
                'Ukuran file maksimal 5 MB.',
        ]);


        try {

            $file =
                $request->file(
                    'file_excel'
                );


            if (
                !$file ||
                !$file->isValid()
            ) {

                return back()
                    ->with(
                        'error',
                        'File Excel tidak dapat dibaca.'
                    );
            }


            /*
            BACA FILE EXCEL
            */

            $spreadsheet =
                IOFactory::load(
                    $file->getRealPath()
                );


            $worksheet =
                $spreadsheet
                    ->getActiveSheet();


            $rows =
                $worksheet->toArray(
                    null,
                    true,
                    true,
                    true
                );


            if (
                count($rows) < 2
            ) {

                return back()
                    ->with(
                        'error',
                        'File Excel tidak memiliki data buku.'
                    );
            }


            /*
            FORMAT HEADER
            */

            $headerRow =
                array_shift($rows);


            $headers = [];


            foreach (
                $headerRow as $column => $header
            ) {

                if ($header === null) {
                    continue;
                }


                $header =
                    strtolower(
                        trim(
                            (string) $header
                        )
                    );


                $header =
                    preg_replace(
                        '/\s+/',
                        '_',
                        $header
                    );


                $headers[$column] =
                    $header;
            }


            /*
            KOLOM WAJIB
            */

            $requiredColumns = [
                'judul',
                'penulis',
                'jenis_buku',
            ];


            foreach (
                $requiredColumns as $required
            ) {

                if (
                    !in_array(
                        $required,
                        $headers,
                        true
                    )
                ) {

                    return back()
                        ->with(
                            'error',
                            'Kolom "' .
                            $required .
                            '" tidak ditemukan di file Excel.'
                        );
                }
            }


            /*
            KOLOM YANG DIPERBOLEHKAN
            */

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


            /*
            PROSES BARIS
            */

            foreach (
                $rows as $index => $row
            ) {

                $nomorBaris =
                    $index + 2;


                $data = [];


                foreach (
                    $headers as $column => $header
                ) {

                    if (
                        !in_array(
                            $header,
                            $allowedColumns,
                            true
                        )
                    ) {
                        continue;
                    }


                    $value =
                        $row[$column] ?? null;


                    if (
                        is_string($value)
                    ) {

                        $value =
                            trim($value);
                    }


                    if (
                        $value === ''
                    ) {

                        $value = null;
                    }


                    $data[$header] =
                        $value;
                }


                /*
                CEK BARIS KOSONG
                */

                $adaIsi = false;


                foreach (
                    $data as $value
                ) {

                    if (
                        $value !== null &&
                        $value !== ''
                    ) {

                        $adaIsi = true;

                        break;
                    }
                }


                if (!$adaIsi) {
                    continue;
                }


                /*
                JUDUL
                */

                if (
                    empty($data['judul'])
                ) {

                    $dilewati++;


                    $barisError[] =
                        'Baris ' .
                        $nomorBaris .
                        ': judul kosong.';


                    continue;
                }


                /*
                PENULIS
                */

                if (
                    empty($data['penulis'])
                ) {

                    $dilewati++;


                    $barisError[] =
                        'Baris ' .
                        $nomorBaris .
                        ': penulis kosong.';


                    continue;
                }


                /*
                JENIS BUKU
                */

                $jenisBuku =
                    strtolower(
                        trim(
                            $data['jenis_buku'] ?? ''
                        )
                    );


                if (
                    !in_array(
                        $jenisBuku,
                        [
                            'literasi',
                            'paket',
                        ],
                        true
                    )
                ) {

                    $dilewati++;


                    $barisError[] =
                        'Baris ' .
                        $nomorBaris .
                        ': jenis_buku harus literasi atau paket.';


                    continue;
                }


                /*
                SEMESTER
                */

                $semester =
                    $data['semester'] ?? null;


                if (
                    $jenisBuku === 'paket'
                ) {

                    if (
                        $semester === null ||
                        $semester === '' ||
                        !is_numeric($semester) ||
                        !in_array(
                            (int) $semester,
                            [1, 2],
                            true
                        )
                    ) {

                        $dilewati++;


                        $barisError[] =
                            'Baris ' .
                            $nomorBaris .
                            ': buku paket wajib memiliki semester 1 atau 2.';


                        continue;
                    }


                    $semester =
                        (int) $semester;

                } else {

                    $semester = null;
                }


                /*
                STOK
                */

                $stok =
                    $data['stok'] ?? 1;


                if (
                    !is_numeric($stok) ||
                    (int) $stok < 1
                ) {

                    $dilewati++;


                    $barisError[] =
                        'Baris ' .
                        $nomorBaris .
                        ': stok harus berupa angka minimal 1.';


                    continue;
                }


                $stok =
                    (int) $stok;


                /*
                TAHUN
                */

                $tahun =
                    $data['tahun'] ?? null;


                if (
                    $tahun !== null &&
                    $tahun !== ''
                ) {

                    if (
                        !is_numeric($tahun)
                    ) {

                        $dilewati++;


                        $barisError[] =
                            'Baris ' .
                            $nomorBaris .
                            ': tahun harus berupa angka.';


                        continue;
                    }


                    $tahun =
                        (int) $tahun;
                }


                /*
                DATA OPSIONAL
                */

                $penerbit =
                    $data['penerbit'] ?? null;

                $kategori =
                    $data['kategori'] ?? null;

                $rak =
                    $data['rak'] ?? null;

                $deskripsi =
                    $data['deskripsi'] ?? null;


                /*
                NORMALISASI KATEGORI EXCEL
                */

                if ($kategori !== null) {

                    $kategori = collect(
                        explode(',', $kategori)
                    )
                        ->map(function ($item) {
                            return trim($item);
                        })
                        ->filter(function ($item) {
                            return $item !== '';
                        })
                        ->unique(function ($item) {
                            return strtolower($item);
                        })
                        ->implode(', ');
                }


                /*
                SIMPAN BUKU
                */

                Book::create([

                    'judul' =>
                        $data['judul'],

                    'penulis' =>
                        $data['penulis'],

                    'penerbit' =>
                        $penerbit,

                    'tahun' =>
                        $tahun,

                    'kategori' =>
                        $kategori,

                    'jenis_buku' =>
                        $jenisBuku,

                    'semester' =>
                        $semester,

                    'rak' =>
                        $rak,

                    'stok' =>
                        $stok,

                    'jumlah_dipinjam' =>
                        0,

                    'deskripsi' =>
                        $deskripsi,

                    'cover' =>
                        null,
                ]);


                $berhasil++;
            }


            /*
            TIDAK ADA DATA BERHASIL
            */

            if (
                $berhasil === 0
            ) {

                $pesan =
                    'Tidak ada data buku yang berhasil diimport.';


                if (
                    count($barisError) > 0
                ) {

                    $pesan .=
                        ' ' .
                        implode(
                            ' ',
                            array_slice(
                                $barisError,
                                0,
                                5
                            )
                        );
                }


                return back()
                    ->with(
                        'error',
                        $pesan
                    );
            }


            /*
            ACTIVITY LOG
            */

            $keterangan =
                'Mengimport ' .
                $berhasil .
                ' buku dari file Excel.';


            if (
                $dilewati > 0
            ) {

                $keterangan .=
                    ' ' .
                    $dilewati .
                    ' baris dilewati.';
            }


            ActivityLog::create([

                'user_id' =>
                    Auth::id(),

                'aktivitas' =>
                    'Import Buku',

                'keterangan' =>
                    $keterangan,
            ]);


            /*
            PESAN HASIL
            */

            $message =
                $berhasil .
                ' buku berhasil diimport.';


            if (
                $dilewati > 0
            ) {

                $message .=
                    ' ' .
                    $dilewati .
                    ' baris dilewati karena data tidak sesuai.';


                if (
                    count($barisError) > 0
                ) {

                    $message .=
                        ' Contoh: ' .
                        implode(
                            ' ',
                            array_slice(
                                $barisError,
                                0,
                                3
                            )
                        );
                }
            }


            /*
            REDIRECT
            */

            return redirect()
                ->route(
                    'petugas.books.index'
                )
                ->with(
                    'success',
                    $message
                );

        } catch (
            \Throwable $e
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal mengimport file Excel: ' .
                    $e->getMessage()
                );
        }
    }


    // ==========================================================
    // DOWNLOAD TEMPLATE EXCEL
    // ==========================================================

    public function downloadTemplate()
    {
        $spreadsheet =
            new Spreadsheet();


        /*
        SHEET UTAMA
        */

        $sheet =
            $spreadsheet
                ->getActiveSheet();


        $sheet->setTitle(
            'Data Buku'
        );


        /*
        HEADER
        */

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


        $sheet->fromArray(
            $headers,
            null,
            'A1'
        );


        /*
        CONTOH DATA
        */

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


        $sheet->fromArray(
            $dataContoh,
            null,
            'A2'
        );


        /*
        STYLE HEADER
        */

        $sheet
            ->getStyle('A1:J1')
            ->getFont()
            ->setBold(true);


        $sheet
            ->getStyle('A1:J1')
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            )
            ->setVertical(
                Alignment::VERTICAL_CENTER
            );


        $sheet
            ->getStyle('A1:J1')
            ->getFill()
            ->setFillType(
                Fill::FILL_SOLID
            )
            ->getStartColor()
            ->setARGB(
                'D9EAF7'
            );


        /*
        BORDER
        */

        $sheet
            ->getStyle(
                'A1:J' .
                (count($dataContoh) + 1)
            )
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                Border::BORDER_THIN
            );


        /*
        ALIGNMENT
        */

        $sheet
            ->getStyle('D2:D1000')
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );


        $sheet
            ->getStyle('G2:G1000')
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );


        $sheet
            ->getStyle('I2:I1000')
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );


        /*
        FREEZE HEADER
        */

        $sheet->freezePane('A2');


        /*
        FILTER
        */

        $sheet->setAutoFilter(
            'A1:J1'
        );


        /*
        LEBAR KOLOM
        */

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


        foreach (
            $widths as $column => $width
        ) {

            $sheet
                ->getColumnDimension($column)
                ->setWidth($width);
        }


        /*
        WRAP TEXT
        */

        $sheet
            ->getStyle('A1:J1000')
            ->getAlignment()
            ->setVertical(
                Alignment::VERTICAL_TOP
            );


        $sheet
            ->getStyle('A1:J1000')
            ->getAlignment()
            ->setWrapText(true);


        /*
        SHEET PETUNJUK
        */

        $petunjuk =
            $spreadsheet
                ->createSheet();


        $petunjuk->setTitle(
            'Petunjuk'
        );


        $petunjuk->fromArray([
            [
                'PETUNJUK IMPORT BUKU',
            ],

            [
                '',
            ],

            [
                'Kolom',
                'Keterangan',
                'Wajib',
            ],

            [
                'judul',
                'Judul buku',
                'Ya',
            ],

            [
                'penulis',
                'Nama penulis/pengarang',
                'Ya',
            ],

            [
                'penerbit',
                'Nama penerbit',
                'Tidak',
            ],

            [
                'tahun',
                'Tahun terbit, berupa angka',
                'Tidak',
            ],

            [
                'kategori',
                'Kategori buku',
                'Tidak',
            ],

            [
                'jenis_buku',
                'literasi atau paket',
                'Ya',
            ],

            [
                'semester',
                '1 atau 2. Wajib untuk buku paket, kosong untuk literasi',
                'Kondisional',
            ],

            [
                'rak',
                'Kode/lokasi rak buku',
                'Tidak',
            ],

            [
                'stok',
                'Jumlah buku tersedia, minimal 1',
                'Ya',
            ],

            [
                'deskripsi',
                'Deskripsi atau sinopsis buku',
                'Tidak',
            ],

            [
                '',
            ],

            [
                'ATURAN',
            ],

            [
                '1',
                'jenis_buku hanya boleh literasi atau paket.',
            ],

            [
                '2',
                'Buku literasi tidak menggunakan semester.',
            ],

            [
                '3',
                'Buku paket wajib memiliki semester 1 atau 2.',
            ],

            [
                '4',
                'Stok minimal 1.',
            ],

            [
                '5',
                'jumlah_dipinjam tidak perlu dimasukkan.',
            ],

            [
                '6',
                'cover tidak perlu dimasukkan karena cover diupload melalui sistem.',
            ],

        ], null, 'A1');


        /*
        STYLE SHEET PETUNJUK
        */

        $petunjuk
            ->getStyle('A1:C3')
            ->getFont()
            ->setBold(true);


        $petunjuk
            ->getStyle('A1:C1')
            ->getFont()
            ->setSize(14);


        $petunjuk
            ->getStyle('A1:C1')
            ->getFill()
            ->setFillType(
                Fill::FILL_SOLID
            )
            ->getStartColor()
            ->setARGB(
                'D9EAF7'
            );


        $petunjuk
            ->getStyle('A3:C3')
            ->getFill()
            ->setFillType(
                Fill::FILL_SOLID
            )
            ->getStartColor()
            ->setARGB(
                'EAF3F8'
            );


        $petunjuk
            ->getStyle('A1:C21')
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                Border::BORDER_THIN
            );


        $petunjuk
            ->getStyle('A1:C21')
            ->getAlignment()
            ->setVertical(
                Alignment::VERTICAL_TOP
            )
            ->setWrapText(true);


        $petunjuk
            ->getColumnDimension('A')
            ->setWidth(22);


        $petunjuk
            ->getColumnDimension('B')
            ->setWidth(70);


        $petunjuk
            ->getColumnDimension('C')
            ->setWidth(18);


        /*
        KEMBALI KE SHEET DATA BUKU
        */

        $spreadsheet->setActiveSheetIndex(0);


        /*
        WRITER
        */

        $writer =
            IOFactory::createWriter(
                $spreadsheet,
                'Xlsx'
            );


        /*
        DOWNLOAD
        */

        return response()->streamDownload(
            function () use ($writer) {

                $writer->save(
                    'php://output'
                );

            },
            'template-import-buku.xlsx',
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }


    // ==========================================================
    // DOWNLOAD DATA BUKU EXCEL
    // ==========================================================

    public function exportExcel()
    {
        $spreadsheet =
            new Spreadsheet();


        $sheet =
            $spreadsheet
                ->getActiveSheet();


        $sheet->setTitle(
            'Data Buku'
        );


        /*
        HEADER
        */

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


        $sheet->fromArray(
            $headers,
            null,
            'A1'
        );


        /*
        DATA BUKU
        */

        $books =
            Book::latest()->get();


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


        /*
        STYLE HEADER
        */

        $sheet->getStyle('A1:K1')
            ->getFont()
            ->setBold(true);


        $sheet->getStyle('A1:K1')
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );


        $sheet->getStyle('A1:K1')
            ->getFill()
            ->setFillType(
                Fill::FILL_SOLID
            )
            ->getStartColor()
            ->setARGB(
                'D9EAF7'
            );


        /*
        BORDER
        */

        $sheet->getStyle(
            'A1:K' .
            max($row - 1, 1)
        )
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                Border::BORDER_THIN
            );


        /*
        WRAP TEXT
        */

        $sheet->getStyle(
            'A1:K' .
            max($row - 1, 1)
        )
            ->getAlignment()
            ->setVertical(
                Alignment::VERTICAL_TOP
            )
            ->setWrapText(true);


        /*
        FILTER
        */

        $sheet->setAutoFilter(
            'A1:K1'
        );


        /*
        LEBAR KOLOM
        */

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


        foreach (
            $widths as $column => $width
        ) {

            $sheet
                ->getColumnDimension($column)
                ->setWidth($width);
        }


        /*
        FREEZE HEADER
        */

        $sheet->freezePane('A2');


        /*
        DOWNLOAD
        */

        $writer =
            IOFactory::createWriter(
                $spreadsheet,
                'Xlsx'
            );


        return response()->streamDownload(
            function () use ($writer) {

                $writer->save(
                    'php://output'
                );

            },
            'data-buku.xlsx',
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }
}