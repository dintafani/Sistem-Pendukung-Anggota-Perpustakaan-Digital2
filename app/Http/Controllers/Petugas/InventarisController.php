<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class InventarisController extends Controller
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
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Inventory::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                $q->where(
                    'kode_inventaris',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'nama_barang',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'lokasi',
                    'like',
                    '%' . $search . '%'
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | KATEGORI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kategori')) {

            $query->where(
                'kategori',
                $request->input('kategori')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KONDISI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kondisi')) {

            $query->where(
                'kondisi',
                $request->input('kondisi')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tanggal')) {

            $query->whereDate(
                'tanggal_pengadaan',
                $request->input('tanggal')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BULAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('bulan')) {

            $query->whereMonth(
                'tanggal_pengadaan',
                $request->input('bulan')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TAHUN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahun')) {

            $query->whereYear(
                'tanggal_pengadaan',
                $request->input('tahun')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $inventaris = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK BERDASARKAN BULAN
        |--------------------------------------------------------------------------
        */

        $statistikQuery = Inventory::query();


        /*
        |--------------------------------------------------------------------------
        | JIKA BULAN DIPILIH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('bulan')) {

            $statistikQuery->whereMonth(
                'tanggal_pengadaan',
                $request->bulan
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL INVENTARIS
        |--------------------------------------------------------------------------
        */

        $totalInventaris = (clone $statistikQuery)->count();


        /*
        |--------------------------------------------------------------------------
        | KONDISI BAIK
        |--------------------------------------------------------------------------
        */

        $totalBaik = (clone $statistikQuery)
            ->where('kondisi', 'baik')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | RUSAK / PERLU PERBAIKAN
        |--------------------------------------------------------------------------
        */

        $totalRusak = (clone $statistikQuery)
            ->whereIn(
                'kondisi',
                [
                    'rusak_ringan',
                    'rusak_berat'
                ]
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'petugas.archive.inventaris',
            compact(
                'inventaris',
                'totalInventaris',
                'totalBaik',
                'totalRusak'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'petugas.archive.inventaris-create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'kode_inventaris' => [
                'required',
                'string',
                'max:100',
                'unique:inventories,kode_inventaris'
            ],

            'nama_barang' => [
                'required',
                'string',
                'max:255'
            ],

            'kategori' => [
                'required',
                'in:elektronik,furniture,peralatan,lainnya'
            ],

            'jumlah' => [
                'required',
                'integer',
                'min:1'
            ],

            'kondisi' => [
                'required',
                'in:baik,rusak_ringan,rusak_berat'
            ],

            'lokasi' => [
                'nullable',
                'string',
                'max:255'
            ],

            'tanggal_pengadaan' => [
                'nullable',
                'date'
            ],

            'keterangan' => [
                'nullable',
                'string'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN INVENTARIS
        |--------------------------------------------------------------------------
        */

        $inventaris = Inventory::create($validated);


        /*
        |--------------------------------------------------------------------------
        | CATAT HISTORI AKTIVITAS
        |--------------------------------------------------------------------------
        */

        ActivityLog::create([

            'user_id' => auth()->id(),

            'aktivitas' => 'Tambah Inventaris',

            'keterangan' =>
                'Menambahkan inventaris "' .
                $inventaris->nama_barang .
                '" dengan kode inventaris "' .
                $inventaris->kode_inventaris .
                '".',

        ]);


        return redirect()
            ->route('petugas.archive.inventaris')
            ->with(
                'success',
                'Inventaris berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $inventaris = Inventory::findOrFail($id);

        return view(
            'petugas.archive.inventaris-show',
            compact('inventaris')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $inventaris = Inventory::findOrFail($id);

        return view(
            'petugas.archive.inventaris-edit',
            compact('inventaris')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $inventaris = Inventory::findOrFail($id);

        $validated = $request->validate([

            'kode_inventaris' => [
                'required',
                'string',
                'max:100',
                'unique:inventories,kode_inventaris,' .
                $inventaris->id
            ],

            'nama_barang' => [
                'required',
                'string',
                'max:255'
            ],

            'kategori' => [
                'required',
                'in:elektronik,furniture,peralatan,lainnya'
            ],

            'jumlah' => [
                'required',
                'integer',
                'min:1'
            ],

            'kondisi' => [
                'required',
                'in:baik,rusak_ringan,rusak_berat'
            ],

            'lokasi' => [
                'nullable',
                'string',
                'max:255'
            ],

            'tanggal_pengadaan' => [
                'nullable',
                'date'
            ],

            'keterangan' => [
                'nullable',
                'string'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE INVENTARIS
        |--------------------------------------------------------------------------
        */

        $inventaris->update($validated);


        /*
        |--------------------------------------------------------------------------
        | CATAT HISTORI AKTIVITAS
        |--------------------------------------------------------------------------
        */

        ActivityLog::create([

            'user_id' => auth()->id(),

            'aktivitas' => 'Edit Inventaris',

            'keterangan' =>
                'Mengubah data inventaris "' .
                $inventaris->nama_barang .
                '" dengan kode inventaris "' .
                $inventaris->kode_inventaris .
                '".',

        ]);


        return redirect()
            ->route('petugas.archive.inventaris')
            ->with(
                'success',
                'Data inventaris berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $inventaris = Inventory::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA UNTUK HISTORI
        |--------------------------------------------------------------------------
        */

        $namaBarang = $inventaris->nama_barang;
        $kodeInventaris = $inventaris->kode_inventaris;


        /*
        |--------------------------------------------------------------------------
        | CATAT HISTORI SEBELUM DATA DIHAPUS
        |--------------------------------------------------------------------------
        */

        ActivityLog::create([

            'user_id' => auth()->id(),

            'aktivitas' => 'Hapus Inventaris',

            'keterangan' =>
                'Menghapus inventaris "' .
                $namaBarang .
                '" dengan kode inventaris "' .
                $kodeInventaris .
                '".',

        ]);


        /*
        |--------------------------------------------------------------------------
        | HAPUS INVENTARIS
        |--------------------------------------------------------------------------
        */

        $inventaris->delete();


        return redirect()
            ->route('petugas.archive.inventaris')
            ->with(
                'success',
                'Inventaris berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT
    |--------------------------------------------------------------------------
    */

    public function export(Request $request)
    {
        return $this->exportInventaris($request);
    }


    /*
    |--------------------------------------------------------------------------
    | EXPORT INVENTARIS
    |--------------------------------------------------------------------------
    */

    public function exportInventaris(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | QUERY
        |--------------------------------------------------------------------------
        */

        $query = Inventory::query();


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
                    'kode_inventaris',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'nama_barang',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'lokasi',
                    'like',
                    '%' . $search . '%'
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | KATEGORI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kategori')) {

            $query->where(
                'kategori',
                $request->input('kategori')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KONDISI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kondisi')) {

            $query->where(
                'kondisi',
                $request->input('kondisi')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tanggal')) {

            $query->whereDate(
                'tanggal_pengadaan',
                $request->input('tanggal')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BULAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('bulan')) {

            $query->whereMonth(
                'tanggal_pengadaan',
                $request->input('bulan')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TAHUN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahun')) {

            $query->whereYear(
                'tanggal_pengadaan',
                $request->input('tahun')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATA HASIL FILTER
        |--------------------------------------------------------------------------
        */

        $inventaris = $query
            ->orderByDesc('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | NAMA BULAN
        |--------------------------------------------------------------------------
        */

        $namaBulan = [

            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',

        ];


        /*
        |--------------------------------------------------------------------------
        | LABEL KATEGORI
        |--------------------------------------------------------------------------
        */

        $kategoriLabels = [

            'elektronik' => 'Elektronik',
            'furniture' => 'Furniture',
            'peralatan' => 'Peralatan',
            'lainnya' => 'Lainnya',

        ];


        /*
        |--------------------------------------------------------------------------
        | LABEL KONDISI
        |--------------------------------------------------------------------------
        */

        $kondisiLabels = [

            'baik' => 'Baik',

            'rusak_ringan' =>
                'Rusak Ringan',

            'rusak_berat' =>
                'Rusak Berat',

        ];


        /*
        |--------------------------------------------------------------------------
        | FILTER TEXT
        |--------------------------------------------------------------------------
        */

        $filterText = [];


        if ($request->filled('search')) {

            $filterText[] =
                'Pencarian: ' .
                trim($request->input('search'));
        }


        if ($request->filled('kategori')) {

            $filterText[] =
                'Kategori: ' .
                (
                    $kategoriLabels[
                        $request->input('kategori')
                    ]
                    ??
                    ucfirst(
                        $request->input('kategori')
                    )
                );
        }


        if ($request->filled('kondisi')) {

            $filterText[] =
                'Kondisi: ' .
                (
                    $kondisiLabels[
                        $request->input('kondisi')
                    ]
                    ??
                    ucfirst(
                        $request->input('kondisi')
                    )
                );
        }


        if ($request->filled('tanggal')) {

            try {

                $filterText[] =
                    'Tanggal: ' .
                    Carbon::parse(
                        $request->input('tanggal')
                    )->format('d-m-Y');

            } catch (\Exception $e) {

                $filterText[] =
                    'Tanggal: ' .
                    $request->input('tanggal');
            }
        }


        if ($request->filled('bulan')) {

            $filterText[] =
                'Bulan: ' .
                (
                    $namaBulan[
                        (int) $request->input('bulan')
                    ]
                    ??
                    $request->input('bulan')
                );
        }


        if ($request->filled('tahun')) {

            $filterText[] =
                'Tahun: ' .
                $request->input('tahun');
        }


        if (empty($filterText)) {

            $filterText[] =
                'Semua Data Inventaris';
        }


        $filterTextString =
            implode(
                ' | ',
                $filterText
            );


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $jumlahData =
            $inventaris->count();

        $totalJumlah =
            $inventaris->sum('jumlah');


        /*
        |--------------------------------------------------------------------------
        | SPREADSHEET
        |--------------------------------------------------------------------------
        */

        $spreadsheet =
            new Spreadsheet();

        $sheet =
            $spreadsheet->getActiveSheet();

        $sheet->setTitle(
            'Arsip Inventaris'
        );


        /*
        |--------------------------------------------------------------------------
        | PAGE SETUP
        |--------------------------------------------------------------------------
        */

        $pageSetup =
            $sheet->getPageSetup();

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

        $sheet->mergeCells(
            'A1:I1'
        );

        $sheet->setCellValue(
            'A1',
            'ARSIP INVENTARIS PERPUSTAKAAN'
        );

        $sheet->getStyle(
            'A1:I1'
        )->applyFromArray([

            'font' => [

                'name' =>
                    'Calibri',

                'size' =>
                    18,

                'bold' =>
                    true,

                'color' => [

                    'rgb' =>
                        $this->white,

                ],
            ],

            'fill' => [

                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [

                    'rgb' =>
                        $this->orange,

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
            ->setRowHeight(34);


        /*
        |--------------------------------------------------------------------------
        | SUB JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells(
            'A2:I2'
        );

        $sheet->setCellValue(
            'A2',
            'SISTEM INFORMASI PERPUSTAKAAN DIGITAL'
        );

        $sheet->getStyle(
            'A2:I2'
        )->applyFromArray([

            'font' => [

                'name' =>
                    'Calibri',

                'size' =>
                    11,

                'bold' =>
                    true,

                'color' => [

                    'rgb' =>
                        $this->orangeDark,

                ],
            ],

            'fill' => [

                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [

                    'rgb' =>
                        $this->orangeLight,

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
            ->setRowHeight(24);


        /*
        |--------------------------------------------------------------------------
        | INFORMASI LAPORAN
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells(
            'A4:I4'
        );

        $sheet->setCellValue(
            'A4',
            'INFORMASI LAPORAN'
        );

        $sheet->getStyle(
            'A4:I4'
        )->applyFromArray([

            'font' => [

                'bold' =>
                    true,

                'color' => [

                    'rgb' =>
                        $this->white,

                ],
            ],

            'fill' => [

                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [

                    'rgb' =>
                        $this->orangeDark,

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
                'Filter',
                $filterTextString,
            ],

            [
                'Jumlah Data',
                $jumlahData . ' inventaris',
            ],

            [
                'Total Jumlah Barang',
                $totalJumlah . ' barang',
            ],

            [
                'Tanggal Cetak',
                now()->format(
                    'd-m-Y H:i'
                ),
            ],

        ];


        $row = 5;


        foreach ($info as $item) {

            $sheet->setCellValue(
                'A' . $row,
                $item[0]
            );

            $sheet->mergeCells(
                'B' . $row . ':I' . $row
            );

            $sheet->setCellValue(
                'B' . $row,
                $item[1]
            );


            $sheet->getStyle(
                'A' . $row . ':I' . $row
            )->applyFromArray([

                'borders' => [

                    'allBorders' => [

                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [

                            'rgb' =>
                                $this->borderColor,

                        ],
                    ],
                ],

                'alignment' => [

                    'vertical' =>
                        Alignment::VERTICAL_CENTER,

                    'wrapText' =>
                        true,
                ],
            ]);


            $sheet->getStyle(
                'A' . $row
            )->applyFromArray([

                'font' => [

                    'bold' =>
                        true,

                    'color' => [

                        'rgb' =>
                            $this->black,

                    ],
                ],

                'fill' => [

                    'fillType' =>
                        Fill::FILL_SOLID,

                    'startColor' => [

                        'rgb' =>
                            $this->grayLight,

                    ],
                ],
            ]);


            $sheet->getRowDimension($row)
                ->setRowHeight(22);

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
            'Kode Inventaris',
            'Nama Barang',
            'Kategori',
            'Jumlah',
            'Kondisi',
            'Lokasi',
            'Tanggal Pengadaan',
            'Keterangan',

        ];


        foreach (
            $headers as $index => $header
        ) {

            $column =
                chr(
                    ord('A') + $index
                );

            $sheet->setCellValue(
                $column . $headerRow,
                $header
            );
        }


        $sheet->getStyle(
            'A' . $headerRow .
            ':I' . $headerRow
        )->applyFromArray([

            'font' => [

                'name' =>
                    'Calibri',

                'size' =>
                    11,

                'bold' =>
                    true,

                'color' => [

                    'rgb' =>
                        $this->white,

                ],
            ],

            'fill' => [

                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [

                    'rgb' =>
                        $this->orange,

                ],
            ],

            'borders' => [

                'allBorders' => [

                    'borderStyle' =>
                        Border::BORDER_THIN,

                    'color' => [

                        'rgb' =>
                            $this->orangeDark,

                    ],
                ],
            ],

            'alignment' => [

                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,

                'vertical' =>
                    Alignment::VERTICAL_CENTER,

                'wrapText' =>
                    true,
            ],
        ]);


        $sheet->getRowDimension(
            $headerRow
        )->setRowHeight(30);


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $dataRow = 12;

        $no = 1;


        foreach (
            $inventaris as $item
        ) {

            /*
            |--------------------------------------------------------------------------
            | TANGGAL
            |--------------------------------------------------------------------------
            */

            $tanggal = '-';


            if (
                !empty(
                    $item->tanggal_pengadaan
                )
            ) {

                try {

                    $tanggal =
                        Carbon::parse(
                            $item->tanggal_pengadaan
                        )->format('d-m-Y');

                } catch (\Exception $e) {

                    $tanggal =
                        $item->tanggal_pengadaan;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | KATEGORI
            |--------------------------------------------------------------------------
            */

            $kategoriLabel =
                $kategoriLabels[
                    $item->kategori
                ]
                ??
                ucfirst(
                    $item->kategori ?? '-'
                );


            /*
            |--------------------------------------------------------------------------
            | KONDISI
            |--------------------------------------------------------------------------
            */

            $kondisiLabel =
                $kondisiLabels[
                    $item->kondisi
                ]
                ??
                ucfirst(
                    $item->kondisi ?? '-'
                );


            /*
            |--------------------------------------------------------------------------
            | DATA CELL
            |--------------------------------------------------------------------------
            */

            $sheet->setCellValue(
                'A' . $dataRow,
                $no
            );

            $sheet->setCellValue(
                'B' . $dataRow,
                $item->kode_inventaris ?? '-'
            );

            $sheet->setCellValue(
                'C' . $dataRow,
                $item->nama_barang ?? '-'
            );

            $sheet->setCellValue(
                'D' . $dataRow,
                $kategoriLabel
            );

            $sheet->setCellValue(
                'E' . $dataRow,
                $item->jumlah ?? 0
            );

            $sheet->setCellValue(
                'F' . $dataRow,
                $kondisiLabel
            );

            $sheet->setCellValue(
                'G' . $dataRow,
                $item->lokasi ?? '-'
            );

            $sheet->setCellValue(
                'H' . $dataRow,
                $tanggal
            );

            $sheet->setCellValue(
                'I' . $dataRow,
                $item->keterangan ?? '-'
            );


            /*
            |--------------------------------------------------------------------------
            | ZEBRA ROW
            |--------------------------------------------------------------------------
            */

            $fillColor =
                $no % 2 === 0
                    ? $this->orangeLight
                    : $this->white;


            $sheet->getStyle(
                'A' . $dataRow .
                ':I' . $dataRow
            )->applyFromArray([

                'fill' => [

                    'fillType' =>
                        Fill::FILL_SOLID,

                    'startColor' => [

                        'rgb' =>
                            $fillColor,

                    ],
                ],

                'font' => [

                    'name' =>
                        'Calibri',

                    'size' =>
                        10,

                    'color' => [

                        'rgb' =>
                            $this->black,

                    ],
                ],

                'borders' => [

                    'allBorders' => [

                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [

                            'rgb' =>
                                $this->borderColor,

                        ],
                    ],
                ],

                'alignment' => [

                    'vertical' =>
                        Alignment::VERTICAL_CENTER,

                    'wrapText' =>
                        true,
                ],
            ]);


            /*
            |--------------------------------------------------------------------------
            | ALIGNMENT
            |--------------------------------------------------------------------------
            */

            foreach (
                [
                    'A',
                    'D',
                    'E',
                    'F',
                    'H'
                ] as $column
            ) {

                $sheet->getStyle(
                    $column . $dataRow
                )->applyFromArray([

                    'alignment' => [

                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,

                        'wrapText' =>
                            true,
                    ],
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | WARNA KONDISI
            |--------------------------------------------------------------------------
            */

            $conditionFill =
                $this->grayLight;

            $conditionFont =
                $this->black;


            if (
                $item->kondisi === 'baik'
            ) {

                $conditionFill =
                    'DCFCE7';

                $conditionFont =
                    '166534';

            } elseif (
                $item->kondisi === 'rusak_ringan'
            ) {

                $conditionFill =
                    'FEF3C7';

                $conditionFont =
                    '92400E';

            } elseif (
                $item->kondisi === 'rusak_berat'
            ) {

                $conditionFill =
                    'FEE2E2';

                $conditionFont =
                    'B91C1C';
            }


            $sheet->getStyle(
                'F' . $dataRow
            )->applyFromArray([

                'font' => [

                    'bold' =>
                        true,

                    'color' => [

                        'rgb' =>
                            $conditionFont,

                    ],
                ],

                'fill' => [

                    'fillType' =>
                        Fill::FILL_SOLID,

                    'startColor' => [

                        'rgb' =>
                            $conditionFill,

                    ],
                ],

                'alignment' => [

                    'horizontal' =>
                        Alignment::HORIZONTAL_CENTER,

                    'vertical' =>
                        Alignment::VERTICAL_CENTER,

                    'wrapText' =>
                        true,
                ],

                'borders' => [

                    'allBorders' => [

                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [

                            'rgb' =>
                                $this->borderColor,

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
        | DATA KOSONG
        |--------------------------------------------------------------------------
        */

        if (
            $inventaris->isEmpty()
        ) {

            $sheet->mergeCells(
                'A' . $dataRow .
                ':I' . $dataRow
            );

            $sheet->setCellValue(
                'A' . $dataRow,
                'Tidak ada data inventaris berdasarkan filter yang dipilih.'
            );

            $sheet->getStyle(
                'A' . $dataRow .
                ':I' . $dataRow
            )->applyFromArray([

                'font' => [

                    'italic' =>
                        true,

                    'color' => [

                        'rgb' =>
                            $this->gray,

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
                                $this->borderColor,

                        ],
                    ],
                ],
            ]);

            $dataRow++;
        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $totalRow = $dataRow;


        $sheet->mergeCells(
            'A' . $totalRow .
            ':D' . $totalRow
        );

        $sheet->setCellValue(
            'A' . $totalRow,
            'TOTAL DATA INVENTARIS'
        );

        $sheet->setCellValue(
            'E' . $totalRow,
            $jumlahData
        );

        $sheet->mergeCells(
            'F' . $totalRow .
            ':H' . $totalRow
        );

        $sheet->setCellValue(
            'F' . $totalRow,
            'TOTAL JUMLAH BARANG'
        );

        $sheet->setCellValue(
            'I' . $totalRow,
            $totalJumlah
        );


        $sheet->getStyle(
            'A' . $totalRow .
            ':I' . $totalRow
        )->applyFromArray([

            'font' => [

                'bold' =>
                    true,

                'color' => [

                    'rgb' =>
                        $this->black,

                ],
            ],

            'fill' => [

                'fillType' =>
                    Fill::FILL_SOLID,

                'startColor' => [

                    'rgb' =>
                        'FFEDD5',

                ],
            ],

            'borders' => [

                'allBorders' => [

                    'borderStyle' =>
                        Border::BORDER_THIN,

                    'color' => [

                        'rgb' =>
                            $this->orangeDark,

                    ],
                ],
            ],

            'alignment' => [

                'vertical' =>
                    Alignment::VERTICAL_CENTER,

                'wrapText' =>
                    true,
            ],
        ]);


        $sheet->getStyle(
            'E' . $totalRow
        )->applyFromArray([

            'alignment' => [

                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,
            ],
        ]);


        $sheet->getStyle(
            'I' . $totalRow
        )->applyFromArray([

            'alignment' => [

                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,
            ],
        ]);


        $sheet->getRowDimension(
            $totalRow
        )->setRowHeight(28);


        /*
        |--------------------------------------------------------------------------
        | AUTO FILTER
        |--------------------------------------------------------------------------
        */

        if ($inventaris->isNotEmpty()) {

            $sheet->setAutoFilter(
                'A' . $headerRow .
                ':I' . ($dataRow - 1)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FREEZE HEADER
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane(
            'A12'
        );


        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTH
        |--------------------------------------------------------------------------
        */

        $sheet->getColumnDimension('A')
            ->setWidth(7);

        $sheet->getColumnDimension('B')
            ->setWidth(20);

        $sheet->getColumnDimension('C')
            ->setWidth(30);

        $sheet->getColumnDimension('D')
            ->setWidth(18);

        $sheet->getColumnDimension('E')
            ->setWidth(10);

        $sheet->getColumnDimension('F')
            ->setWidth(22);

        $sheet->getColumnDimension('G')
            ->setWidth(22);

        $sheet->getColumnDimension('H')
            ->setWidth(20);

        $sheet->getColumnDimension('I')
            ->setWidth(40);


        /*
        |--------------------------------------------------------------------------
        | FOOTER / TANDA TANGAN
        |--------------------------------------------------------------------------
        */

        $signatureRow =
            $totalRow + 3;


        $sheet->mergeCells(
            'G' . $signatureRow .
            ':I' . $signatureRow
        );

        $sheet->setCellValue(
            'G' . $signatureRow,
            'Petugas Perpustakaan'
        );

        $sheet->getStyle(
            'G' . $signatureRow .
            ':I' . $signatureRow
        )->applyFromArray([

            'font' => [

                'bold' =>
                    true,

            ],

            'alignment' => [

                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,
            ],
        ]);


        $signatureNameRow =
            $signatureRow + 5;


        $sheet->mergeCells(
            'G' . $signatureNameRow .
            ':I' . $signatureNameRow
        );

        $sheet->setCellValue(
            'G' . $signatureNameRow,
            '____________________________'
        );

        $sheet->getStyle(
            'G' . $signatureNameRow .
            ':I' . $signatureNameRow
        )->applyFromArray([

            'font' => [

                'bold' =>
                    true,

            ],

            'alignment' => [

                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | FONT GLOBAL
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle(
            'A1:I' . $signatureNameRow
        )->getFont()
            ->setName('Calibri');


        /*
        |--------------------------------------------------------------------------
        | PRINT SETTINGS
        |--------------------------------------------------------------------------
        */

        $sheet->getPageSetup()
            ->setPrintArea(
                'A1:I' . $signatureNameRow
            );


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $filename =
            'arsip-inventaris-' .
            now()->format(
                'Y-m-d-H-i-s'
            ) .
            '.xlsx';


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        return response()->streamDownload(

            function () use ($spreadsheet) {

                $writer =
                    new Xlsx(
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