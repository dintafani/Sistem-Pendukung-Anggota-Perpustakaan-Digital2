<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class ArchiveController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WARNA EXCEL
    |--------------------------------------------------------------------------
    */

    private string $orange = 'F97316';
    private string $orangeDark = 'EA580C';
    private string $orangeLight = 'FFF7ED';

    private string $cream = 'FFFBEB';
    private string $white = 'FFFFFF';
    private string $black = '1F2937';
    private string $gray = '6B7280';
    private string $grayLight = 'F3F4F6';
    private string $borderColor = 'D1D5DB';


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ARSIP
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $bulan = now()->month;
        $tahun = now()->year;

        $totalTransaksi = Borrowing::whereMonth(
            'tanggal_pinjam',
            $bulan
        )
        ->whereYear(
            'tanggal_pinjam',
            $tahun
        )
        ->count();

        $totalDipinjam = Borrowing::where(
            'status',
            'dipinjam'
        )->count();

        $totalKembali = Borrowing::where(
            'status',
            'dikembalikan'
        )
        ->whereMonth(
            'tanggal_kembali',
            $bulan
        )
        ->whereYear(
            'tanggal_kembali',
            $tahun
        )
        ->count();

        $transaksiTerbaru = Borrowing::with([
            'user',
            'book'
        ])
        ->whereMonth(
            'tanggal_pinjam',
            $bulan
        )
        ->whereYear(
            'tanggal_pinjam',
            $tahun
        )

        ->orderByDesc('tanggal_pinjam')
->orderByDesc('created_at')
->orderByDesc('id')
->take(8)
->get();


        return view(
            'petugas.archive.index',
            compact(
                'totalTransaksi',
                'totalDipinjam',
                'totalKembali',
                'transaksiTerbaru'
            )
        );
    }


   /*
|--------------------------------------------------------------------------
| ARSIP ANGGOTA
|--------------------------------------------------------------------------
*/

public function anggota(Request $request)
{
    $query = User::query()
        ->where('role', 'siswa');

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

if ($request->filled('search')) {

    $search = trim(
        $request->input('search')
    );

    $query->where(function ($q) use ($search) {

        $q->where(
            'name',
            'like',
            '%' . $search . '%'
        )
        ->orWhere(
            'nis',
            'like',
            '%' . $search . '%'
        )
        ->orWhere(
            'kelas',
            'like',
            '%' . $search . '%'
        )
        ->orWhere(
            'email',
            'like',
            '%' . $search . '%'
        );

    });
}


/*
|--------------------------------------------------------------------------
| FILTER TANGGAL
|--------------------------------------------------------------------------
|
*/

if ($request->filled('tanggal')) {

    $query->whereDay(
        'created_at',
        $request->input('tanggal')
    );
}


if ($request->filled('bulan')) {

    $query->whereMonth(
        'created_at',
        $request->input('bulan')
    );
}


if ($request->filled('tahun')) {

    $query->whereYear(
        'created_at',
        $request->input('tahun')
    );
}


    /*
    |--------------------------------------------------------------------------
    | FILTER STATUS
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        $query->where(
            'status',
            $request->input('status')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATA ANGGOTA
    |--------------------------------------------------------------------------
    */

    $anggota = $query
        ->latest('created_at')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'petugas.archive.anggota',
        compact('anggota')
    );
}

    /*
    |--------------------------------------------------------------------------
    | HAPUS ANGGOTA
    |--------------------------------------------------------------------------
    */

    public function destroyAnggota(User $user)
    {
        if ($user->role !== 'siswa') {

            return back()->with(
                'error',
                'Data petugas tidak dapat dihapus dari arsip anggota.'
            );
        }

        $masihPinjam = Borrowing::where(
            'user_id',
            $user->id
        )
        ->whereIn(
            'status',
            [
                'dipinjam',
                'menunggu_pengembalian'
            ]
        )
        ->exists();

        if ($masihPinjam) {

            return back()->with(
                'error',
                'Anggota masih memiliki buku yang belum dikembalikan.'
            );
        }

        $nama = $user->name;

        $user->delete();
        ActivityLog::create([
    'user_id'    => Auth::id(),
    'aktivitas'  => 'Hapus Anggota',
    'keterangan' => 'Menghapus anggota "' . $nama . '"',
]);


        return back()->with(
            'success',
            'Data anggota "' . $nama . '" berhasil dihapus.'
        );
    }

/*
|--------------------------------------------------------------------------
| EXPORT ANGGOTA EXCEL
|--------------------------------------------------------------------------
*/

public function exportAnggota(Request $request)
{
    $search = trim(
        $request->input('search', '')
    );

    $tanggal = $request->input('tanggal');

    $bulan = $request->input('bulan');

    $tahun = $request->input('tahun');

    $status = $request->input('status');


    /*
    |--------------------------------------------------------------------------
    | QUERY
    |--------------------------------------------------------------------------
    */

    $query = User::query()
        ->where('role', 'siswa');


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($search !== '') {

        $query->where(function ($q) use ($search) {

            $q->where(
                'name',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'nis',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'kelas',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'email',
                'like',
                '%' . $search . '%'
            );

        });
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER TANGGAL
    |--------------------------------------------------------------------------
    */

    if (!empty($tanggal)) {

        $query->whereDay(
            'created_at',
            $tanggal
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER BULAN
    |--------------------------------------------------------------------------
    */

    if (!empty($bulan)) {

        $query->whereMonth(
            'created_at',
            $bulan
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER TAHUN
    |--------------------------------------------------------------------------
    */

    if (!empty($tahun)) {

        $query->whereYear(
            'created_at',
            $tahun
        );
    }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (!empty($status)) {

            $query->where(
                'status',
                $status
            );
        }


        $anggota = $query
            ->latest('created_at')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | STATUS TEXT
        |--------------------------------------------------------------------------
        */

        switch ($status) {

            case 'aktif':
                $statusText = 'Terverifikasi';
                break;

            case 'pending':
                $statusText = 'Belum Terverifikasi';
                break;

            case 'ditolak':
                $statusText = 'Ditolak';
                break;

            default:
                $statusText = 'Semua Status';
                break;
        }


        $searchText = $search !== ''
            ? $search
            : '-';

        $tanggalText = !empty($tanggal)
            ? Carbon::parse($tanggal)->format('d-m-Y')
            : 'Semua Tanggal';


        /*
        |--------------------------------------------------------------------------
        | SPREADSHEET
        |--------------------------------------------------------------------------
        */

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Arsip Anggota');


        /*
        |--------------------------------------------------------------------------
        | PAGE SETUP
        |--------------------------------------------------------------------------
        */

        $pageSetup = $sheet->getPageSetup();

        $pageSetup->setOrientation(
            PageSetup::ORIENTATION_LANDSCAPE
        );

        $pageSetup->setPaperSize(
            PageSetup::PAPERSIZE_A4
        );

        $pageSetup->setFitToWidth(1);

        $pageSetup->setFitToHeight(0);


        $sheet->getPageMargins()
            ->setTop(0.4)
            ->setBottom(0.4)
            ->setLeft(0.3)
            ->setRight(0.3);


        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:G1');

        $sheet->setCellValue(
            'A1',
            'ARSIP ANGGOTA PERPUSTAKAAN'
        );

        $sheet->getStyle('A1:G1')->applyFromArray([

            'font' => [
                'name' => 'Calibri',
                'size' => 18,
                'bold' => true,
                'color' => [
                    'rgb' => $this->white
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => $this->orange
                ],
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,
                'vertical' =>
                    Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)
            ->setRowHeight(32);


        /*
        |--------------------------------------------------------------------------
        | SUB JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A2:G2');

        $sheet->setCellValue(
            'A2',
            'SISTEM INFORMASI PERPUSTAKAAN DIGITAL'
        );

        $sheet->getStyle('A2:G2')->applyFromArray([

            'font' => [
                'name' => 'Calibri',
                'size' => 11,
                'bold' => true,
                'color' => [
                    'rgb' => $this->orangeDark
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => $this->orangeLight
                ],
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,
                'vertical' =>
                    Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(2)
            ->setRowHeight(23);


        /*
        |--------------------------------------------------------------------------
        | INFORMASI
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A4:G4');

        $sheet->setCellValue(
            'A4',
            'INFORMASI LAPORAN'
        );

        $sheet->getStyle('A4:G4')->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => $this->white
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => $this->orangeDark
                ],
            ],

            'alignment' => [
                'vertical' =>
                    Alignment::VERTICAL_CENTER,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | DATA INFORMASI
        |--------------------------------------------------------------------------
        */

        $info = [

            [
                'Pencarian',
                $searchText
            ],

            [
                'Tanggal Daftar',
                $tanggalText
            ],

            [
                'Status',
                $statusText
            ],

            [
                'Jumlah Anggota',
                $anggota->count() . ' anggota'
            ],

            [
                'Tanggal Cetak',
                now()->format('d-m-Y H:i')
            ],

        ];

        $row = 5;

        foreach ($info as $item) {

            $sheet->setCellValue(
                'A' . $row,
                $item[0]
            );

            $sheet->mergeCells(
                'B' . $row . ':G' . $row
            );

            $sheet->setCellValue(
                'B' . $row,
                $item[1]
            );

            /*
             * JANGAN menggunakan getStyle()->getAlignment()
             * pada range.
             */
            $sheet->getStyle(
                'A' . $row . ':G' . $row
            )->applyFromArray([

                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>
                            Border::BORDER_THIN,
                        'color' => [
                            'rgb' =>
                                $this->borderColor
                        ],
                    ],
                ],

                'alignment' => [
                    'vertical' =>
                        Alignment::VERTICAL_CENTER,
                ],
            ]);

            $sheet->getStyle(
                'A' . $row
            )->applyFromArray([

                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' =>
                            $this->black
                    ],
                ],

                'fill' => [
                    'fillType' =>
                        Fill::FILL_SOLID,

                    'startColor' => [
                        'rgb' =>
                            $this->grayLight
                    ],
                ],
            ]);

            $sheet->getRowDimension($row)
                ->setRowHeight(21);

            $row++;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER TABEL
        |--------------------------------------------------------------------------
        */

        $headerRow = 11;

        $headers = [
            'No',
            'Nama',
            'NIS',
            'Kelas',
            'Email',
            'Status',
            'Tanggal Daftar',
        ];

        foreach ($headers as $index => $header) {

            $column = chr(
                ord('A') + $index
            );

            $sheet->setCellValue(
                $column . $headerRow,
                $header
            );
        }

        $sheet->getStyle(
            'A' . $headerRow . ':G' . $headerRow
        )->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => $this->white
                ],
            ],

            'fill' => [
                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [
                    'rgb' =>
                        $this->orange
                ],
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' =>
                        Border::BORDER_THIN,

                    'color' => [
                        'rgb' =>
                            $this->orangeDark
                    ],
                ],
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,

                'vertical' =>
                    Alignment::VERTICAL_CENTER,

                'wrapText' => true,
            ],
        ]);

        $sheet->getRowDimension($headerRow)
            ->setRowHeight(28);


        /*
        |--------------------------------------------------------------------------
        | DATA ANGGOTA
        |--------------------------------------------------------------------------
        */

        $dataRow = 12;

        if ($anggota->count() > 0) {

            $no = 1;

            foreach ($anggota as $user) {

                $nama = $user->name ?? '-';
                $nis = $user->nis ?? '-';
                $kelas = $user->kelas ?? '-';
                $email = $user->email ?? '-';

                $tanggalDaftar = $user->created_at
                    ? $user->created_at->format('d-m-Y')
                    : '-';


                switch ($user->status) {

                    case 'aktif':
                        $statusAnggota =
                            'Terverifikasi';
                        break;

                    case 'pending':
                        $statusAnggota =
                            'Belum Terverifikasi';
                        break;

                    case 'ditolak':
                        $statusAnggota =
                            'Ditolak';
                        break;

                    default:
                        $statusAnggota =
                            ucfirst(
                                $user->status ?? '-'
                            );
                        break;
                }


                $sheet->setCellValue(
                    'A' . $dataRow,
                    $no
                );

                $sheet->setCellValue(
                    'B' . $dataRow,
                    $nama
                );

                $sheet->setCellValue(
                    'C' . $dataRow,
                    $nis
                );

                $sheet->setCellValue(
                    'D' . $dataRow,
                    $kelas
                );

                $sheet->setCellValue(
                    'E' . $dataRow,
                    $email
                );

                $sheet->setCellValue(
                    'F' . $dataRow,
                    $statusAnggota
                );

                $sheet->setCellValue(
                    'G' . $dataRow,
                    $tanggalDaftar
                );


                /*
                |--------------------------------------------------------------------------
                | ZEBRA
                |--------------------------------------------------------------------------
                */

                $fillColor =
                    $no % 2 === 0
                        ? $this->orangeLight
                        : $this->white;


                $sheet->getStyle(
                    'A' . $dataRow . ':G' . $dataRow
                )->applyFromArray([

                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' =>
                                $fillColor
                        ],
                    ],

                    'font' => [
                        'name' => 'Calibri',
                        'size' => 10,
                        'color' => [
                            'rgb' =>
                                $this->black
                        ],
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>
                                Border::BORDER_THIN,

                            'color' => [
                                'rgb' =>
                                    $this->borderColor
                            ],
                        ],
                    ],

                    'alignment' => [
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,

                        'wrapText' => true,
                    ],
                ]);


                /*
                |--------------------------------------------------------------------------
                | ALIGNMENT PER KOLOM
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    'A' . $dataRow
                )->applyFromArray([

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                    ],
                ]);


                $sheet->getStyle(
                    'C' . $dataRow
                )->applyFromArray([

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                    ],
                ]);


                $sheet->getStyle(
                    'D' . $dataRow
                )->applyFromArray([

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                    ],
                ]);


                $sheet->getStyle(
                    'F' . $dataRow
                )->applyFromArray([

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                    ],
                ]);


                $sheet->getStyle(
                    'G' . $dataRow
                )->applyFromArray([

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                    ],
                ]);


                /*
                |--------------------------------------------------------------------------
                | WARNA STATUS
                |--------------------------------------------------------------------------
                */

                $statusFill = $this->grayLight;
                $statusFont = $this->black;

                switch ($user->status) {

                    case 'aktif':

                        $statusFill = 'DCFCE7';
                        $statusFont = '166534';

                        break;

                    case 'pending':

                        $statusFill = 'FEF3C7';
                        $statusFont = '92400E';

                        break;

                    case 'ditolak':

                        $statusFill = 'FEE2E2';
                        $statusFont = 'B91C1C';

                        break;
                }


                $sheet->getStyle(
                    'F' . $dataRow
                )->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' =>
                                $statusFont
                        ],
                    ],

                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' =>
                                $statusFill
                        ],
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,

                        'wrapText' => true,
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>
                                Border::BORDER_THIN,

                            'color' => [
                                'rgb' =>
                                    $this->borderColor
                            ],
                        ],
                    ],
                ]);


                $sheet->getRowDimension(
                    $dataRow
                )->setRowHeight(30);


                $dataRow++;
                $no++;
            }

        } else {

            $sheet->mergeCells(
                'A' . $dataRow . ':G' . $dataRow
            );

            $sheet->setCellValue(
                'A' . $dataRow,
                'Tidak ada anggota yang ditemukan.'
            );

            $sheet->getStyle(
                'A' . $dataRow . ':G' . $dataRow
            )->applyFromArray([

                'font' => [
                    'italic' => true,
                    'color' => [
                        'rgb' =>
                            $this->gray
                    ],
                ],

                'alignment' => [
                    'horizontal' =>
                        Alignment::HORIZONTAL_CENTER,

                    'vertical' =>
                        Alignment::VERTICAL_CENTER,
                ],

                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [
                            'rgb' =>
                                $this->borderColor
                        ],
                    ],
                ],
            ]);

            $dataRow++;
        }


        /*
        |--------------------------------------------------------------------------
        | AUTO FILTER
        |--------------------------------------------------------------------------
        */

        $sheet->setAutoFilter(
            'A' . $headerRow .
            ':G' . ($dataRow - 1)
        );


        /*
        |--------------------------------------------------------------------------
        | FREEZE
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane('A12');


        /*
        |--------------------------------------------------------------------------
        | LEBAR KOLOM
        |--------------------------------------------------------------------------
        */

        $sheet->getColumnDimension('A')
            ->setWidth(7);

        $sheet->getColumnDimension('B')
            ->setWidth(30);

        $sheet->getColumnDimension('C')
            ->setWidth(18);

        $sheet->getColumnDimension('D')
            ->setWidth(15);

        $sheet->getColumnDimension('E')
            ->setWidth(35);

        $sheet->getColumnDimension('F')
            ->setWidth(25);

        $sheet->getColumnDimension('G')
            ->setWidth(20);


        /*
        |--------------------------------------------------------------------------
        | TANDA TANGAN
        |--------------------------------------------------------------------------
        */

        $signatureRow = $dataRow + 3;

        $sheet->mergeCells(
            'F' . $signatureRow . ':G' . $signatureRow
        );

        $sheet->setCellValue(
            'F' . $signatureRow,
            'Petugas Perpustakaan'
        );

        $sheet->getStyle(
            'F' . $signatureRow . ':G' . $signatureRow
        )->applyFromArray([

            'font' => [
                'bold' => true,
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,
            ],
        ]);


        $signatureNameRow =
            $signatureRow + 5;

        $sheet->mergeCells(
            'F' . $signatureNameRow . ':G' . $signatureNameRow
        );

        $sheet->setCellValue(
            'F' . $signatureNameRow,
            '____________________________'
        );

        $sheet->getStyle(
            'F' . $signatureNameRow . ':G' . $signatureNameRow
        )->applyFromArray([

            'font' => [
                'bold' => true,
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | FONT
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle(
            'A1:G' . $signatureNameRow
        )->getFont()
            ->setName('Calibri');


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $filename =
            'arsip-anggota-' .
            now()->format('Y-m-d-H-i-s') .
            '.xlsx';


        return response()->streamDownload(

            function () use ($spreadsheet) {

                $writer = new Xlsx(
                    $spreadsheet
                );

                $writer->save(
                    'php://output'
                );
            },

            $filename,

            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

                'Cache-Control' =>
                    'max-age=0, no-cache, no-store, must-revalidate',

                'Pragma' =>
                    'no-cache',

                'Expires' =>
                    '0',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HISTORI AKTIVITAS PETUGAS
    |--------------------------------------------------------------------------
    */

public function aktivitas(Request $request)
{
    $query = ActivityLog::with('user');


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim(
            $request->input('search')
        );

        $query->where(function ($q) use ($search) {

            $q->where(
                'aktivitas',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'keterangan',
                'like',
                '%' . $search . '%'
            )
            ->orWhereHas(
                'user',
                function ($user) use ($search) {

                    $user->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );

                }
            );

        });
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER AKTIVITAS
    |--------------------------------------------------------------------------
    */

    if ($request->filled('aktivitas')) {

        $query->where(
            'aktivitas',
            $request->input('aktivitas')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER TANGGAL
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tanggal')) {

        $query->whereDay(
            'created_at',
            $request->input('tanggal')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER BULAN
    |--------------------------------------------------------------------------
    */

    if ($request->filled('bulan')) {

        $query->whereMonth(
            'created_at',
            $request->input('bulan')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER TAHUN
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tahun')) {

        $query->whereYear(
            'created_at',
            $request->input('tahun')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HASIL
    |--------------------------------------------------------------------------
    */

    $activities = $query
        ->latest('created_at')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'petugas.archive.aktivitas',
        compact('activities')
    );
}

/*
|--------------------------------------------------------------------------
| EXPORT HISTORI AKTIVITAS
|--------------------------------------------------------------------------
*/

public function exportAktivitas(Request $request)
{
    $search = trim(
        $request->input('search', '')
    );

    $aktivitas = $request->input('aktivitas');

    $tanggal = $request->input('tanggal');

    $bulan = $request->input('bulan');

    $tahun = $request->input('tahun');

    $query = ActivityLog::with('user');


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($search !== '') {

        $query->where(function ($q) use ($search) {

            $q->where(
                'aktivitas',
                'like',
                '%' . $search . '%'
            )
            ->orWhere(
                'keterangan',
                'like',
                '%' . $search . '%'
            )
            ->orWhereHas(
                'user',
                function ($user) use ($search) {

                    $user->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );

                }
            );

        });
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER AKTIVITAS
    |--------------------------------------------------------------------------
    */

    if (!empty($aktivitas)) {

        $query->where(
            'aktivitas',
            $aktivitas
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER TANGGAL
    |--------------------------------------------------------------------------
    */

    if (!empty($tanggal)) {

        $query->whereDay(
            'created_at',
            $tanggal
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER BULAN
    |--------------------------------------------------------------------------
    */

    if (!empty($bulan)) {

        $query->whereMonth(
            'created_at',
            $bulan
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER TAHUN
    |--------------------------------------------------------------------------
    */

    if (!empty($tahun)) {

        $query->whereYear(
            'created_at',
            $tahun
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL DATA
    |--------------------------------------------------------------------------
    */

    $activities = $query
        ->latest('created_at')
        ->get();

        /*
        |--------------------------------------------------------------------------
        | SPREADSHEET
        |--------------------------------------------------------------------------
        */

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle(
            'Histori Aktivitas'
        );


        /*
        |--------------------------------------------------------------------------
        | PAGE SETUP
        |--------------------------------------------------------------------------
        */

        $pageSetup = $sheet->getPageSetup();

        $pageSetup->setOrientation(
            PageSetup::ORIENTATION_LANDSCAPE
        );

        $pageSetup->setPaperSize(
            PageSetup::PAPERSIZE_A4
        );

        $pageSetup->setFitToWidth(1);

        $pageSetup->setFitToHeight(0);


        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:E1');

        $sheet->setCellValue(
            'A1',
            'LAPORAN HISTORI AKTIVITAS PERPUSTAKAAN'
        );

        $sheet->getStyle(
            'A1:E1'
        )->applyFromArray([

            'font' => [
                'size' => 18,
                'bold' => true,
                'color' => [
                    'rgb' =>
                        $this->white
                ],
            ],

            'fill' => [
                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [
                    'rgb' =>
                        $this->orange
                ],
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,

                'vertical' =>
                    Alignment::VERTICAL_CENTER,
            ],
        ]);


        $sheet->mergeCells('A2:E2');

        $sheet->setCellValue(
            'A2',
            'SISTEM INFORMASI PERPUSTAKAAN DIGITAL'
        );

        $sheet->getStyle(
            'A2:E2'
        )->applyFromArray([

            'font' => [
                'size' => 11,
                'bold' => true,
                'color' => [
                    'rgb' =>
                        $this->orangeDark
                ],
            ],

            'fill' => [
                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [
                    'rgb' =>
                        $this->orangeLight
                ],
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,

                'vertical' =>
                    Alignment::VERTICAL_CENTER,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | INFORMASI
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A4:E4');

        $sheet->setCellValue(
            'A4',
            'INFORMASI LAPORAN'
        );

        $sheet->getStyle(
            'A4:E4'
        )->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' =>
                        $this->white
                ],
            ],

            'fill' => [
                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [
                    'rgb' =>
                        $this->orangeDark
                ],
            ],
        ]);


        $tanggalText = $tanggal
            ? Carbon::parse($tanggal)->format('d-m-Y')
            : 'Semua Tanggal';

        $aktivitasText = $aktivitas
            ?: 'Semua Aktivitas';

        $searchText = $search
            ?: '-';


        $info = [

            [
                'Tanggal',
                $tanggalText
            ],

            [
                'Aktivitas',
                $aktivitasText
            ],

            [
                'Pencarian',
                $searchText
            ],

            [
                'Jumlah Data',
                $activities->count() .
                ' aktivitas'
            ],

            [
                'Tanggal Cetak',
                now()->format('d-m-Y H:i')
            ],

        ];


        $row = 5;

        foreach ($info as $item) {

            $sheet->setCellValue(
                'A' . $row,
                $item[0]
            );

            $sheet->mergeCells(
                'B' . $row . ':E' . $row
            );

            $sheet->setCellValue(
                'B' . $row,
                $item[1]
            );

            $sheet->getStyle(
                'A' . $row . ':E' . $row
            )->applyFromArray([

                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [
                            'rgb' =>
                                $this->borderColor
                        ],
                    ],
                ],

                'alignment' => [
                    'vertical' =>
                        Alignment::VERTICAL_CENTER,
                ],
            ]);

            $sheet->getStyle(
                'A' . $row
            )->applyFromArray([

                'font' => [
                    'bold' => true,
                ],

                'fill' => [
                    'fillType' =>
                        Fill::FILL_SOLID,

                    'startColor' => [
                        'rgb' =>
                            $this->grayLight
                    ],
                ],
            ]);

            $row++;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $headerRow = 11;

        $headers = [
            'No',
            'Petugas',
            'Aktivitas',
            'Keterangan',
            'Waktu',
        ];

        foreach ($headers as $index => $header) {

            $column = chr(
                ord('A') + $index
            );

            $sheet->setCellValue(
                $column . $headerRow,
                $header
            );
        }


        $sheet->getStyle(
            'A' . $headerRow . ':E' . $headerRow
        )->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' =>
                        $this->white
                ],
            ],

            'fill' => [
                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [
                    'rgb' =>
                        $this->orange
                ],
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' =>
                        Border::BORDER_THIN,

                    'color' => [
                        'rgb' =>
                            $this->orangeDark
                    ],
                ],
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,

                'vertical' =>
                    Alignment::VERTICAL_CENTER,

                'wrapText' => true,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $dataRow = 12;

        if ($activities->count()) {

            $no = 1;

            foreach ($activities as $activity) {

                $sheet->setCellValue(
                    'A' . $dataRow,
                    $no
                );

                $sheet->setCellValue(
                    'B' . $dataRow,
                    optional($activity->user)->name
                    ?? 'System'
                );

                $sheet->setCellValue(
                    'C' . $dataRow,
                    $activity->aktivitas
                    ?? '-'
                );

                $sheet->setCellValue(
                    'D' . $dataRow,
                    $activity->keterangan
                    ?? '-'
                );

                $sheet->setCellValue(
                    'E' . $dataRow,
                    $activity->created_at
                        ? $activity->created_at
                            ->format('d-m-Y H:i')
                        : '-'
                );


                $fillColor =
                    $no % 2 === 0
                        ? $this->orangeLight
                        : $this->white;


                $sheet->getStyle(
                    'A' . $dataRow . ':E' . $dataRow
                )->applyFromArray([

                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' =>
                                $fillColor
                        ],
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>
                                Border::BORDER_THIN,

                            'color' => [
                                'rgb' =>
                                    $this->borderColor
                            ],
                        ],
                    ],

                    'alignment' => [
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,

                        'wrapText' => true,
                    ],
                ]);


                $sheet->getStyle(
                    'A' . $dataRow
                )->applyFromArray([

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                    ],
                ]);


                $sheet->getStyle(
                    'E' . $dataRow
                )->applyFromArray([

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                    ],
                ]);


                $dataRow++;
                $no++;
            }

        } else {

            $sheet->mergeCells(
                'A' . $dataRow . ':E' . $dataRow
            );

            $sheet->setCellValue(
                'A' . $dataRow,
                'Tidak ada aktivitas yang ditemukan.'
            );

            $sheet->getStyle(
                'A' . $dataRow . ':E' . $dataRow
            )->applyFromArray([

                'alignment' => [
                    'horizontal' =>
                        Alignment::HORIZONTAL_CENTER,

                    'vertical' =>
                        Alignment::VERTICAL_CENTER,
                ],

                'font' => [
                    'italic' => true,
                    'color' => [
                        'rgb' =>
                            $this->gray
                    ],
                ],
            ]);

            $dataRow++;
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $sheet->setAutoFilter(
            'A' . $headerRow .
            ':E' . ($dataRow - 1)
        );

        $sheet->freezePane('A12');


        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTH
        |--------------------------------------------------------------------------
        */

        $sheet->getColumnDimension('A')
            ->setWidth(7);

        $sheet->getColumnDimension('B')
            ->setWidth(25);

        $sheet->getColumnDimension('C')
            ->setWidth(25);

        $sheet->getColumnDimension('D')
            ->setWidth(55);

        $sheet->getColumnDimension('E')
            ->setWidth(22);


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $filename =
            'laporan-histori-aktivitas-' .
            now()->format('Y-m-d-H-i-s') .
            '.xlsx';


        return response()->streamDownload(

            function () use ($spreadsheet) {

                $writer = new Xlsx(
                    $spreadsheet
                );

                $writer->save(
                    'php://output'
                );
            },

            $filename,

            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

                'Cache-Control' =>
                    'max-age=0, no-cache, no-store, must-revalidate',

                'Pragma' =>
                    'no-cache',

                'Expires' =>
                    '0',
            ]
        );
    }


   public function transaksi(Request $request)
{
    $query = Borrowing::with([
        'user',
        'book'
    ]);


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim(
            $request->input('search')
        );

        $query->where(function ($q) use ($search) {

            $q->whereHas(
                'user',
                function ($userQuery) use ($search) {

                    $userQuery->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );
                }
            )

            ->orWhereHas(
                'book',
                function ($bookQuery) use ($search) {

                    $bookQuery->where(
                        'judul',
                        'like',
                        '%' . $search . '%'
                    );
                }
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        $query->where(
            'status',
            $request->input('status')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | JENIS BUKU
    |--------------------------------------------------------------------------
    */

    if ($request->filled('jenis')) {

        $query->whereHas(
            'book',
            function ($bookQuery) use ($request) {

                $bookQuery->where(
                    'jenis_buku',
                    $request->input('jenis')
                );

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER TANGGAL
    |--------------------------------------------------------------------------
    */

    if ($request->filled('hari')) {

        $query->whereDay(
            'tanggal_pinjam',
            $request->input('hari')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER BULAN
    |--------------------------------------------------------------------------
    */

    if ($request->filled('bulan')) {

        $query->whereMonth(
            'tanggal_pinjam',
            $request->input('bulan')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER TAHUN
    |--------------------------------------------------------------------------
    */

    if ($request->filled('tahun')) {

        $query->whereYear(
            'tanggal_pinjam',
            $request->input('tahun')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HASIL
    |--------------------------------------------------------------------------
    */

    $borrowings = $query
        ->orderByDesc('tanggal_pinjam')
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->get();


    return view(
        'petugas.archive.transaksi',
        compact('borrowings')
    );
}
/*
|--------------------------------------------------------------------------
| EXPORT TRANSAKSI
|--------------------------------------------------------------------------
*/

public function exportTransaksi(Request $request)
{
    $search = trim(
        $request->input('search', '')
    );

    $status = $request->input('status');

    $jenis = $request->input('jenis');

    $hari = $request->input('hari');

    $bulan = $request->input('bulan');

    $tahun = $request->input('tahun');

    /*
    |--------------------------------------------------------------------------
    | TANGGAL
    |--------------------------------------------------------------------------
    */

    $tanggal = $hari;


    $query = Borrowing::with([
        'user',
        'book'
    ]);


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($search !== '') {

        $query->where(function ($q) use ($search) {

            $q->whereHas(
                'user',
                function ($userQuery) use ($search) {

                    $userQuery->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );
                }
            )

            ->orWhereHas(
                'book',
                function ($bookQuery) use ($search) {

                    $bookQuery->where(
                        'judul',
                        'like',
                        '%' . $search . '%'
                    );
                }
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    if (!empty($status)) {

        $query->where(
            'status',
            $status
        );
    }


    /*
    |--------------------------------------------------------------------------
    | JENIS BUKU
    |--------------------------------------------------------------------------
    */

    if (!empty($jenis)) {

        $query->whereHas(
            'book',
            function ($bookQuery) use ($jenis) {

                $bookQuery->where(
                    'jenis_buku',
                    $jenis
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER HARI
    |--------------------------------------------------------------------------
    */

    if (!empty($hari)) {

        $query->whereDay(
            'tanggal_pinjam',
            $hari
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER BULAN
    |--------------------------------------------------------------------------
    */

    if (!empty($bulan)) {

        $query->whereMonth(
            'tanggal_pinjam',
            $bulan
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER TAHUN
    |--------------------------------------------------------------------------
    */

    if (!empty($tahun)) {

        $query->whereYear(
            'tanggal_pinjam',
            $tahun
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL DATA
    |--------------------------------------------------------------------------
    */

    $borrowings = $query
        ->orderByDesc('tanggal_pinjam')
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | STATUS TEXT
    |--------------------------------------------------------------------------
    */

    switch ($status) {

        case 'menunggu':
            $namaStatus = 'Menunggu Verifikasi';
            break;

        case 'dipinjam':
            $namaStatus = 'Sedang Dipinjam';
            break;

        case 'menunggu_pengembalian':
            $namaStatus = 'Menunggu Pengembalian';
            break;

        case 'dikembalikan':
            $namaStatus = 'Dikembalikan';
            break;

        case 'ditolak':
            $namaStatus = 'Ditolak';
            break;

        default:
            $namaStatus = 'Semua Status';
            break;
    }

        /*
        |--------------------------------------------------------------------------
        | SPREADSHEET
        |--------------------------------------------------------------------------
        */

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle(
            'Arsip Transaksi'
        );


        /*
        |--------------------------------------------------------------------------
        | PAGE SETUP
        |--------------------------------------------------------------------------
        */

        $pageSetup = $sheet->getPageSetup();

        $pageSetup->setOrientation(
            PageSetup::ORIENTATION_LANDSCAPE
        );

        $pageSetup->setPaperSize(
            PageSetup::PAPERSIZE_A4
        );

        $pageSetup->setFitToWidth(1);

        $pageSetup->setFitToHeight(0);


        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:F1');

        $sheet->setCellValue(
            'A1',
            'ARSIP TRANSAKSI PEMINJAMAN PERPUSTAKAAN'
        );

        $sheet->getStyle(
            'A1:F1'
        )->applyFromArray([

            'font' => [
                'size' => 18,
                'bold' => true,
                'color' => [
                    'rgb' =>
                        $this->white
                ],
            ],

            'fill' => [
                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [
                    'rgb' =>
                        $this->orange
                ],
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,

                'vertical' =>
                    Alignment::VERTICAL_CENTER,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | SUB JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A2:F2');

        $sheet->setCellValue(
            'A2',
            'SISTEM INFORMASI PERPUSTAKAAN DIGITAL'
        );

        $sheet->getStyle(
            'A2:F2'
        )->applyFromArray([

            'font' => [
                'size' => 11,
                'bold' => true,
                'color' => [
                    'rgb' =>
                        $this->orangeDark
                ],
            ],

            'fill' => [
                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [
                    'rgb' =>
                        $this->orangeLight
                ],
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,

                'vertical' =>
                    Alignment::VERTICAL_CENTER,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | INFORMASI
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A4:F4');

        $sheet->setCellValue(
            'A4',
            'INFORMASI LAPORAN'
        );

        $sheet->getStyle(
            'A4:F4'
        )->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' =>
                        $this->white
                ],
            ],

            'fill' => [
                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [
                    'rgb' =>
                        $this->orangeDark
                ],
            ],
        ]);


        $info = [

            [
                'Pencarian',
                $search ?: '-'
            ],

            [
                'Status',
                $namaStatus
            ],

            [
                'Tanggal',
                $tanggal
                    ? Carbon::parse($tanggal)
                        ->format('d-m-Y')
                    : 'Semua Tanggal'
            ],

            [
                'Jumlah Transaksi',
                $borrowings->count() .
                ' transaksi'
            ],

            [
                'Tanggal Cetak',
                now()->format('d-m-Y H:i')
            ],

        ];


        $row = 5;


        foreach ($info as $item) {

            $sheet->setCellValue(
                'A' . $row,
                $item[0]
            );

            $sheet->mergeCells(
                'B' . $row . ':F' . $row
            );

            $sheet->setCellValue(
                'B' . $row,
                $item[1]
            );


            $sheet->getStyle(
                'A' . $row . ':F' . $row
            )->applyFromArray([

                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [
                            'rgb' =>
                                $this->borderColor
                        ],
                    ],
                ],

                'alignment' => [
                    'vertical' =>
                        Alignment::VERTICAL_CENTER,
                ],
            ]);


            $sheet->getStyle(
                'A' . $row
            )->applyFromArray([

                'font' => [
                    'bold' => true,
                ],

                'fill' => [
                    'fillType' =>
                        Fill::FILL_SOLID,

                    'startColor' => [
                        'rgb' =>
                            $this->grayLight
                    ],
                ],
            ]);


            $row++;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $headerRow = 11;

        $headers = [

            'No',
            'Nama Siswa',
            'Judul Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status',

        ];


        foreach ($headers as $index => $header) {

            $column = chr(
                ord('A') + $index
            );

            $sheet->setCellValue(
                $column . $headerRow,
                $header
            );
        }


        $sheet->getStyle(
            'A' . $headerRow . ':F' . $headerRow
        )->applyFromArray([

            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' =>
                        $this->white
                ],
            ],

            'fill' => [
                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [
                    'rgb' =>
                        $this->orange
                ],
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' =>
                        Border::BORDER_THIN,

                    'color' => [
                        'rgb' =>
                            $this->orangeDark
                    ],
                ],
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,

                'vertical' =>
                    Alignment::VERTICAL_CENTER,

                'wrapText' => true,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $dataRow = 12;

        $no = 1;


        foreach ($borrowings as $item) {

            $sheet->setCellValue(
                'A' . $dataRow,
                $no
            );

            $sheet->setCellValue(
                'B' . $dataRow,
                optional($item->user)->name
                ?? '-'
            );

            $sheet->setCellValue(
                'C' . $dataRow,
                optional($item->book)->judul
                ?? '-'
            );

            $sheet->setCellValue(
                'D' . $dataRow,
                $item->tanggal_pinjam
                    ? Carbon::parse(
                        $item->tanggal_pinjam
                    )->format('d-m-Y')
                    : '-'
            );

            $sheet->setCellValue(
                'E' . $dataRow,
                $item->tanggal_kembali
                    ? Carbon::parse(
                        $item->tanggal_kembali
                    )->format('d-m-Y')
                    : 'Belum kembali'
            );


            switch ($item->status) {

                case 'menunggu':
                    $statusText =
                        'Menunggu Verifikasi';
                    break;

                case 'dipinjam':
                    $statusText =
                        'Sedang Dipinjam';
                    break;

                case 'menunggu_pengembalian':
                    $statusText =
                        'Menunggu Pengembalian';
                    break;

                case 'dikembalikan':
                    $statusText =
                        'Dikembalikan';
                    break;

                case 'ditolak':
                    $statusText =
                        'Ditolak';
                    break;

                default:
                    $statusText =
                        $item->status ?? '-';
                    break;
            }


            $sheet->setCellValue(
                'F' . $dataRow,
                $statusText
            );


            /*
            |--------------------------------------------------------------------------
            | ZEBRA
            |--------------------------------------------------------------------------
            */

            $fillColor =
                $no % 2 === 0
                    ? $this->orangeLight
                    : $this->white;


            $sheet->getStyle(
                'A' . $dataRow . ':F' . $dataRow
            )->applyFromArray([

                'fill' => [
                    'fillType' =>
                        Fill::FILL_SOLID,

                    'startColor' => [
                        'rgb' =>
                            $fillColor
                    ],
                ],

                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [
                            'rgb' =>
                                $this->borderColor
                        ],
                    ],
                ],

                'alignment' => [
                    'vertical' =>
                        Alignment::VERTICAL_CENTER,

                    'wrapText' => true,
                ],
            ]);


            /*
            |--------------------------------------------------------------------------
            | ALIGNMENT
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle(
                'A' . $dataRow
            )->applyFromArray([

                'alignment' => [
                    'horizontal' =>
                        Alignment::HORIZONTAL_CENTER,
                ],
            ]);


            $sheet->getStyle(
                'D' . $dataRow
            )->applyFromArray([

                'alignment' => [
                    'horizontal' =>
                        Alignment::HORIZONTAL_CENTER,
                ],
            ]);


            $sheet->getStyle(
                'E' . $dataRow
            )->applyFromArray([

                'alignment' => [
                    'horizontal' =>
                        Alignment::HORIZONTAL_CENTER,
                ],
            ]);


            $sheet->getStyle(
                'F' . $dataRow
            )->applyFromArray([

                'alignment' => [
                    'horizontal' =>
                        Alignment::HORIZONTAL_CENTER,
                ],
            ]);


            /*
            |--------------------------------------------------------------------------
            | STATUS COLOR
            |--------------------------------------------------------------------------
            */

            $statusFill = $this->grayLight;
            $statusFont = $this->black;


            switch ($item->status) {

                case 'menunggu':

                    $statusFill = 'FEF3C7';
                    $statusFont = '92400E';

                    break;

                case 'dipinjam':

                    $statusFill = 'DBEAFE';
                    $statusFont = '1D4ED8';

                    break;

                case 'menunggu_pengembalian':

                    $statusFill = 'FFEDD5';
                    $statusFont = 'C2410C';

                    break;

                case 'dikembalikan':

                    $statusFill = 'DCFCE7';
                    $statusFont = '166534';

                    break;

                case 'ditolak':

                    $statusFill = 'FEE2E2';
                    $statusFont = 'B91C1C';

                    break;
            }


            $sheet->getStyle(
                'F' . $dataRow
            )->applyFromArray([

                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' =>
                            $statusFont
                    ],
                ],

                'fill' => [
                    'fillType' =>
                        Fill::FILL_SOLID,

                    'startColor' => [
                        'rgb' =>
                            $statusFill
                    ],
                ],

                'alignment' => [
                    'horizontal' =>
                        Alignment::HORIZONTAL_CENTER,

                    'vertical' =>
                        Alignment::VERTICAL_CENTER,

                    'wrapText' => true,
                ],

                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [
                            'rgb' =>
                                $this->borderColor
                        ],
                    ],
                ],
            ]);


            $sheet->getRowDimension(
                $dataRow
            )->setRowHeight(30);


            $dataRow++;
            $no++;
        }


        /*
        |--------------------------------------------------------------------------
        | EMPTY DATA
        |--------------------------------------------------------------------------
        */

        if ($borrowings->isEmpty()) {

            $sheet->mergeCells(
                'A' . $dataRow . ':F' . $dataRow
            );

            $sheet->setCellValue(
                'A' . $dataRow,
                'Tidak ada transaksi yang ditemukan.'
            );

            $sheet->getStyle(
                'A' . $dataRow . ':F' . $dataRow
            )->applyFromArray([

                'font' => [
                    'italic' => true,
                    'color' => [
                        'rgb' =>
                            $this->gray
                    ],
                ],

                'alignment' => [
                    'horizontal' =>
                        Alignment::HORIZONTAL_CENTER,

                    'vertical' =>
                        Alignment::VERTICAL_CENTER,
                ],

                'borders' => [
                    'allBorders' => [
                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [
                            'rgb' =>
                                $this->borderColor
                        ],
                    ],
                ],
            ]);

            $dataRow++;
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER & FREEZE
        |--------------------------------------------------------------------------
        */

        $sheet->setAutoFilter(
            'A' . $headerRow .
            ':F' . ($dataRow - 1)
        );

        $sheet->freezePane('A12');


        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTH
        |--------------------------------------------------------------------------
        */

        $sheet->getColumnDimension('A')
            ->setWidth(7);

        $sheet->getColumnDimension('B')
            ->setWidth(28);

        $sheet->getColumnDimension('C')
            ->setWidth(48);

        $sheet->getColumnDimension('D')
            ->setWidth(20);

        $sheet->getColumnDimension('E')
            ->setWidth(20);

        $sheet->getColumnDimension('F')
            ->setWidth(28);


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        $filename =
            'arsip-transaksi-' .
            now()->format('Y-m-d-H-i-s') .
            '.xlsx';


        return response()->streamDownload(

            function () use ($spreadsheet) {

                $writer = new Xlsx(
                    $spreadsheet
                );

                $writer->save(
                    'php://output'
                );
            },

            $filename,

            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

                'Cache-Control' =>
                    'max-age=0, no-cache, no-store, must-revalidate',

                'Pragma' =>
                    'no-cache',

                'Expires' =>
                    '0',
            ]
        );
    }
}