<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AttendanceController extends Controller
{
    /**
     * =========================================================
     * FORM DAFTAR HADIR PUBLIK
     * =========================================================
     */
    public function create()
    {
        return view('attendance.create');
    }


    /**
     * =========================================================
     * SIMPAN DAFTAR HADIR
     * =========================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',

            'instansi' => 'nullable|string|max:255',

            'jenis_pengunjung' =>
                'required|in:Siswa,Guru,Petugas,Magang/PKL,Tamu/Kunjungan,Lainnya',

            'kelas' => 'nullable|string|max:100',

            'tujuan' => 'nullable|string|max:1000',

        ], [

            'nama.required' =>
                'Nama wajib diisi.',

            'jenis_pengunjung.required' =>
                'Jenis pengunjung wajib dipilih.',

            'jenis_pengunjung.in' =>
                'Jenis pengunjung tidak valid.',

        ]);


        Attendance::create([

            'nama' =>
                $validated['nama'],

            'instansi' =>
                $validated['instansi'] ?? null,

            'jenis_pengunjung' =>
                $validated['jenis_pengunjung'],

            'kelas' =>
                $validated['kelas'] ?? null,

            'tujuan' =>
                $validated['tujuan'] ?? null,

            'waktu_masuk' =>
                now(),

        ]);


        return redirect()
            ->route('attendance.create')
            ->with(
                'success',
                'Daftar hadir berhasil dicatat. Terima kasih telah berkunjung.'
            );
    }


    /**
     * =========================================================
     * DAFTAR HADIR UNTUK PETUGAS
     *
     * Filter:
     * - Search
     * - Jenis Pengunjung
     * - Tanggal
     * - Bulan
     * - Tahun
     *
     * Jika tidak ada filter tanggal/bulan/tahun:
     * - Menampilkan 7 hari terakhir
     *
     * Statistik:
     * - Total Pengunjung = mengikuti filter tabel
     * - Semua Kunjungan = mengikuti bulan dan tahun
     * =========================================================
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | QUERY UTAMA
        |--------------------------------------------------------------------------
        */

        $query = Attendance::query();


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'nama',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'instansi',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'kelas',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'tujuan',
                    'like',
                    '%' . $search . '%'
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER JENIS PENGUNJUNG
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jenis_pengunjung')) {

            $query->where(
                'jenis_pengunjung',
                $request->jenis_pengunjung
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tanggal')) {

            $query->whereDay(
                'waktu_masuk',
                $request->tanggal
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER BULAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('bulan')) {

            $query->whereMonth(
                'waktu_masuk',
                $request->bulan
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahun')) {

            $query->whereYear(
                'waktu_masuk',
                $request->tahun
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DEFAULT 7 HARI TERAKHIR
        |--------------------------------------------------------------------------
        */

        if (
            !$request->filled('tanggal') &&
            !$request->filled('bulan') &&
            !$request->filled('tahun')
        ) {

            $query->whereBetween(
                'waktu_masuk',
                [
                    now()
                        ->subDays(6)
                        ->startOfDay(),

                    now()
                        ->endOfDay()
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATA TABEL
        |--------------------------------------------------------------------------
        */

        $attendances = $query
            ->latest('waktu_masuk')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | SEMUA KUNJUNGAN
        |
        | Hanya mengikuti:
        | - Bulan
        | - Tahun
        |
        | Tidak mengikuti:
        | - Search
        | - Jenis pengunjung
        | - Tanggal
        | - Default 7 hari
        |--------------------------------------------------------------------------
        */

        $allVisitsQuery = Attendance::query();


        if ($request->filled('bulan')) {

            $allVisitsQuery->whereMonth(
                'waktu_masuk',
                $request->bulan
            );
        }


        if ($request->filled('tahun')) {

            $allVisitsQuery->whereYear(
                'waktu_masuk',
                $request->tahun
            );
        }


        $allVisits =
            $allVisitsQuery->count();


        /*
        |--------------------------------------------------------------------------
        | DATA TAHUN
        |--------------------------------------------------------------------------
        |
        | Tetap dikirim untuk kompatibilitas dengan view lama.
        | Filter tahun pada view Semua Kunjungan sekarang bisa diketik bebas.
        |--------------------------------------------------------------------------
        */

        $years = Attendance::query()
            ->selectRaw(
                'YEAR(waktu_masuk) as tahun'
            )
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'attendance.index',
            compact(
                'attendances',
                'years',
                'allVisits'
            )
        );
    }


    /**
     * =========================================================
     * HALAMAN SEMUA KUNJUNGAN
     *
     * View:
     * resources/views/attendance/show.blade.php
     *
     * Filter:
     * - Bulan
     * - Tahun
     * - Jenis Pengunjung
     *
     * Tahun dapat diisi secara bebas.
     *
     * Tidak menggunakan:
     * - Search
     * - Tanggal
     * - Batas 7 hari
     * =========================================================
     */
    public function show(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | QUERY SEMUA KUNJUNGAN
        |--------------------------------------------------------------------------
        */

        $query = Attendance::query();


        /*
        |--------------------------------------------------------------------------
        | FILTER BULAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('bulan')) {

            $query->whereMonth(
                'waktu_masuk',
                $request->bulan
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN
        |--------------------------------------------------------------------------
        |
        | Tahun bisa berupa tahun apa pun yang diketik pengguna.
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahun')) {

            $query->whereYear(
                'waktu_masuk',
                $request->tahun
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER JENIS PENGUNJUNG
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jenis_pengunjung')) {

            $query->where(
                'jenis_pengunjung',
                $request->jenis_pengunjung
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATA KUNJUNGAN
        |--------------------------------------------------------------------------
        */

        $attendances = $query
            ->latest('waktu_masuk')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | DATA TAHUN
        |--------------------------------------------------------------------------
        |
        | Tetap disediakan jika view masih menggunakan $years.
        |--------------------------------------------------------------------------
        */

        $years = Attendance::query()
            ->selectRaw(
                'YEAR(waktu_masuk) as tahun'
            )
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');


        /*
        |--------------------------------------------------------------------------
        | TOTAL DATA SESUAI FILTER
        |--------------------------------------------------------------------------
        */

        $totalVisits = $query->count();


        /*
        |--------------------------------------------------------------------------
        | KIRIM KE VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'attendance.show',
            compact(
                'attendances',
                'years',
                'totalVisits'
            )
        );
    }


    /**
     * =========================================================
     * EXPORT EXCEL DAFTAR HADIR
     *
     * Filter:
     * - Search
     * - Jenis Pengunjung
     * - Tanggal
     * - Bulan
     * - Tahun
     *
     * Jika tidak ada tanggal/bulan/tahun:
     * - Export 7 hari terakhir
     * =========================================================
     */
    public function exportExcel(Request $request)
    {
        $query = Attendance::query();


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'nama',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'instansi',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'kelas',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'tujuan',
                    'like',
                    '%' . $search . '%'
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER JENIS PENGUNJUNG
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jenis_pengunjung')) {

            $query->where(
                'jenis_pengunjung',
                $request->jenis_pengunjung
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tanggal')) {

            $query->whereDay(
                'waktu_masuk',
                $request->tanggal
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER BULAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('bulan')) {

            $query->whereMonth(
                'waktu_masuk',
                $request->bulan
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahun')) {

            $query->whereYear(
                'waktu_masuk',
                $request->tahun
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DEFAULT 7 HARI TERAKHIR
        |--------------------------------------------------------------------------
        */

        if (
            !$request->filled('tanggal') &&
            !$request->filled('bulan') &&
            !$request->filled('tahun')
        ) {

            $query->whereBetween(
                'waktu_masuk',
                [
                    now()
                        ->subDays(6)
                        ->startOfDay(),

                    now()
                        ->endOfDay()
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        $attendances = $query
            ->latest('waktu_masuk')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | BUAT SPREADSHEET
        |--------------------------------------------------------------------------
        */

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle(
            'Daftar Hadir'
        );


        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'A1',
            'DAFTAR HADIR PERPUSTAKAAN'
        );

        $sheet->mergeCells(
            'A1:G1'
        );

        $sheet->getStyle('A1')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A1')
            ->getFont()
            ->setSize(14);

        $sheet->getStyle('A1')
            ->getAlignment()
            ->setHorizontal(
                'center'
            );


        /*
        |--------------------------------------------------------------------------
        | INFORMASI FILTER
        |--------------------------------------------------------------------------
        */

        $filterParts = [];


        if ($request->filled('search')) {

            $filterParts[] =
                'Pencarian = ' .
                $request->search;
        }


        if ($request->filled('jenis_pengunjung')) {

            $filterParts[] =
                'Jenis = ' .
                $request->jenis_pengunjung;
        }


        if ($request->filled('tanggal')) {

            $filterParts[] =
                'Tanggal = ' .
                $request->tanggal;
        }


        if ($request->filled('bulan')) {

            $months = [

                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',

            ];

            $filterParts[] =
                'Bulan = ' .
                (
                    $months[
                        (int) $request->bulan
                    ]
                    ??
                    $request->bulan
                );
        }


        if ($request->filled('tahun')) {

            $filterParts[] =
                'Tahun = ' .
                $request->tahun;
        }


        if (empty($filterParts)) {

            $filterParts[] =
                '7 hari terakhir';
        }


        $filterInfo =
            'Filter: ' .
            implode(
                ' | ',
                $filterParts
            );


        $sheet->setCellValue(
            'A2',
            $filterInfo
        );

        $sheet->mergeCells(
            'A2:G2'
        );

        $sheet->getStyle('A2')
            ->getAlignment()
            ->setHorizontal(
                'left'
            );


        /*
        |--------------------------------------------------------------------------
        | HEADER TABEL
        |--------------------------------------------------------------------------
        */

        $headers = [

            'No',
            'Nama',
            'Jenis Pengunjung',
            'Instansi',
            'Kelas',
            'Tujuan',
            'Waktu Masuk'

        ];


        $columns = [

            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G'

        ];


        foreach (
            $headers as $index => $header
        ) {

            $sheet->setCellValue(

                $columns[$index] . '4',

                $header

            );
        }


        /*
        |--------------------------------------------------------------------------
        | STYLE HEADER
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:G4')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A4:G4')
            ->getAlignment()
            ->setHorizontal(
                'center'
            );


        /*
        |--------------------------------------------------------------------------
        | ISI DATA
        |--------------------------------------------------------------------------
        */

        $row = 5;


        foreach (
            $attendances as $index => $attendance
        ) {

            $sheet->setCellValue(
                'A' . $row,
                $index + 1
            );


            $sheet->setCellValue(
                'B' . $row,
                $attendance->nama
            );


            $sheet->setCellValue(
                'C' . $row,
                $attendance->jenis_pengunjung
            );


            $sheet->setCellValue(
                'D' . $row,
                $attendance->instansi ?: '-'
            );


            $sheet->setCellValue(
                'E' . $row,
                $attendance->kelas ?: '-'
            );


            $sheet->setCellValue(
                'F' . $row,
                $attendance->tujuan ?: '-'
            );


            $sheet->setCellValue(
                'G' . $row,
                $attendance->waktu_masuk
                    ? $attendance->waktu_masuk
                        ->format('d/m/Y H:i')
                    : '-'
            );


            $row++;
        }


        /*
        |--------------------------------------------------------------------------
        | BORDER
        |--------------------------------------------------------------------------
        */

        if ($row > 5) {

            $sheet->getStyle(
                'A4:G' . ($row - 1)
            )
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                );
        }


        /*
        |--------------------------------------------------------------------------
        | LEBAR KOLOM
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getColumnDimension('A')
            ->setWidth(8);

        $sheet
            ->getColumnDimension('B')
            ->setWidth(30);

        $sheet
            ->getColumnDimension('C')
            ->setWidth(22);

        $sheet
            ->getColumnDimension('D')
            ->setWidth(25);

        $sheet
            ->getColumnDimension('E')
            ->setWidth(15);

        $sheet
            ->getColumnDimension('F')
            ->setWidth(35);

        $sheet
            ->getColumnDimension('G')
            ->setWidth(22);


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $filename =
            'daftar-hadir-' .
            now()->format('Y-m-d-His') .
            '.xlsx';


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $writer =
            new Xlsx($spreadsheet);


        return response()->streamDownload(

            function () use ($writer) {

                $writer->save(
                    'php://output'
                );

            },

            $filename,

            [

                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

            ]

        );
    }


    /**
     * =========================================================
     * EXPORT EXCEL SEMUA KUNJUNGAN
     *
     * View:
     * resources/views/attendance/show.blade.php
     *
     * Filter:
     * - Bulan
     * - Tahun
     * - Jenis Pengunjung
     *
     * Tahun dapat diisi secara bebas.
     *
     * Tanpa filter:
     * - Semua data kunjungan diekspor.
     * =========================================================
     */
    public function exportAll(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | QUERY SEMUA DATA
        |--------------------------------------------------------------------------
        */

        $query = Attendance::query();


        /*
        |--------------------------------------------------------------------------
        | FILTER BULAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('bulan')) {

            $query->whereMonth(
                'waktu_masuk',
                $request->bulan
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahun')) {

            $query->whereYear(
                'waktu_masuk',
                $request->tahun
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER JENIS PENGUNJUNG
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jenis_pengunjung')) {

            $query->where(
                'jenis_pengunjung',
                $request->jenis_pengunjung
            );
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        $attendances = $query
            ->latest('waktu_masuk')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | BUAT SPREADSHEET
        |--------------------------------------------------------------------------
        */

        $spreadsheet =
            new Spreadsheet();

        $sheet =
            $spreadsheet->getActiveSheet();

        $sheet->setTitle(
            'Semua Kunjungan'
        );


        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'A1',
            'SEMUA KUNJUNGAN PERPUSTAKAAN'
        );

        $sheet->mergeCells(
            'A1:G1'
        );

        $sheet->getStyle('A1')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A1')
            ->getFont()
            ->setSize(14);

        $sheet->getStyle('A1')
            ->getAlignment()
            ->setHorizontal(
                'center'
            );


        /*
        |--------------------------------------------------------------------------
        | INFORMASI FILTER
        |--------------------------------------------------------------------------
        */

        $months = [

            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',

        ];


        $filterParts = [];


        if ($request->filled('bulan')) {

            $filterParts[] =
                'Bulan = ' .
                (
                    $months[
                        (int) $request->bulan
                    ]
                    ??
                    $request->bulan
                );
        }


        if ($request->filled('tahun')) {

            $filterParts[] =
                'Tahun = ' .
                $request->tahun;
        }


        if ($request->filled('jenis_pengunjung')) {

            $filterParts[] =
                'Jenis = ' .
                $request->jenis_pengunjung;
        }


        if (empty($filterParts)) {

            $filterParts[] =
                'Semua data kunjungan';
        }


        $filterInfo =
            'Filter: ' .
            implode(
                ' | ',
                $filterParts
            );


        $sheet->setCellValue(
            'A2',
            $filterInfo
        );

        $sheet->mergeCells(
            'A2:G2'
        );


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $headers = [

            'No',
            'Nama',
            'Jenis Pengunjung',
            'Instansi',
            'Kelas',
            'Tujuan',
            'Waktu Masuk'

        ];


        $columns = [

            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G'

        ];


        foreach (
            $headers as $index => $header
        ) {

            $sheet->setCellValue(

                $columns[$index] . '4',

                $header

            );
        }


        /*
        |--------------------------------------------------------------------------
        | STYLE HEADER
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A4:G4')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A4:G4')
            ->getAlignment()
            ->setHorizontal(
                'center'
            );


        /*
        |--------------------------------------------------------------------------
        | ISI DATA
        |--------------------------------------------------------------------------
        */

        $row = 5;


        foreach (
            $attendances as $index => $attendance
        ) {

            $sheet->setCellValue(
                'A' . $row,
                $index + 1
            );


            $sheet->setCellValue(
                'B' . $row,
                $attendance->nama
            );


            $sheet->setCellValue(
                'C' . $row,
                $attendance->jenis_pengunjung
            );


            $sheet->setCellValue(
                'D' . $row,
                $attendance->instansi ?: '-'
            );


            $sheet->setCellValue(
                'E' . $row,
                $attendance->kelas ?: '-'
            );


            $sheet->setCellValue(
                'F' . $row,
                $attendance->tujuan ?: '-'
            );


            $sheet->setCellValue(
                'G' . $row,
                $attendance->waktu_masuk
                    ? $attendance->waktu_masuk
                        ->format('d/m/Y H:i')
                    : '-'
            );


            $row++;
        }


        /*
        |--------------------------------------------------------------------------
        | BORDER
        |--------------------------------------------------------------------------
        */

        if ($row > 5) {

            $sheet->getStyle(
                'A4:G' . ($row - 1)
            )
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                );
        }


        /*
        |--------------------------------------------------------------------------
        | LEBAR KOLOM
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getColumnDimension('A')
            ->setWidth(8);

        $sheet
            ->getColumnDimension('B')
            ->setWidth(30);

        $sheet
            ->getColumnDimension('C')
            ->setWidth(22);

        $sheet
            ->getColumnDimension('D')
            ->setWidth(25);

        $sheet
            ->getColumnDimension('E')
            ->setWidth(15);

        $sheet
            ->getColumnDimension('F')
            ->setWidth(35);

        $sheet
            ->getColumnDimension('G')
            ->setWidth(22);


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $filename =
            'semua-kunjungan-' .
            now()->format('Y-m-d-His') .
            '.xlsx';


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $writer =
            new Xlsx($spreadsheet);


        return response()->streamDownload(

            function () use ($writer) {

                $writer->save(
                    'php://output'
                );

            },

            $filename,

            [

                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

            ]

        );
    }
}