<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\AcademicSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicSettingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR PENGATURAN AKADEMIK
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $academicSettings = AcademicSetting::query()
            ->orderByDesc('id')
            ->paginate(10);

        return view(
            'petugas.academic-settings.index',
            compact('academicSettings')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL PENGATURAN AKADEMIK
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $academicSetting = AcademicSetting::findOrFail($id);

        return view(
            'petugas.academic-settings.show',
            compact('academicSetting')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'petugas.academic-settings.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA BARU
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'tahun_ajaran' => [
                'required',
                'string',
                'max:20',
            ],

            'semester' => [
                'required',
                'integer',
                Rule::in([1, 2]),
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            /*
            |--------------------------------------------------------------------------
            | BATAS BUKU PAKET PER KELAS
            |--------------------------------------------------------------------------
            */

            'batas_paket_kelas_10' => [
                'required',
                'integer',
                'min:0',
            ],

            'batas_paket_kelas_11' => [
                'required',
                'integer',
                'min:0',
            ],

            'batas_paket_kelas_12' => [
                'required',
                'integer',
                'min:0',
            ],

            'aktif' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | MODE UJIAN
        |--------------------------------------------------------------------------
        */

        $modeUjian = false;

        if ($request->boolean('aktif')) {

            AcademicSetting::query()
                ->update([
                    'aktif' => 0,
                    'mode_ujian' => 0,
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | BUAT DATA
        |--------------------------------------------------------------------------
        */

        AcademicSetting::create([

            'tahun_ajaran' =>
                $validated['tahun_ajaran'],

            'semester' =>
                $validated['semester'],

            'tanggal_mulai' =>
                $validated['tanggal_mulai'],

            'tanggal_selesai' =>
                $validated['tanggal_selesai'],

            'batas_paket_kelas_10' =>
                $validated['batas_paket_kelas_10'],

            'batas_paket_kelas_11' =>
                $validated['batas_paket_kelas_11'],

            'batas_paket_kelas_12' =>
                $validated['batas_paket_kelas_12'],

            'aktif' =>
                $request->boolean('aktif'),

            /*
            |----------------------------------------------------------------------
            | SEMESTER BARU TIDAK LANGSUNG MODE UJIAN
            |----------------------------------------------------------------------
            */

            'mode_ujian' =>
                $modeUjian,

        ]);


        return redirect()
            ->route('petugas.academic-settings.index')
            ->with(
                'success',
                'Pengaturan akademik berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $academicSetting =
            AcademicSetting::findOrFail($id);

        return view(
            'petugas.academic-settings.edit',
            compact('academicSetting')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $academicSetting =
            AcademicSetting::findOrFail($id);


        $validated = $request->validate([

            'tahun_ajaran' => [
                'required',
                'string',
                'max:20',
            ],

            'semester' => [
                'required',
                'integer',
                Rule::in([1, 2]),
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            /*
            |--------------------------------------------------------------------------
            | BATAS BUKU PAKET
            |--------------------------------------------------------------------------
            */

            'batas_paket_kelas_10' => [
                'required',
                'integer',
                'min:0',
            ],

            'batas_paket_kelas_11' => [
                'required',
                'integer',
                'min:0',
            ],

            'batas_paket_kelas_12' => [
                'required',
                'integer',
                'min:0',
            ],

            'aktif' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | JIKA DIUBAH MENJADI AKTIF
        | MAKA SEMESTER LAIN DINONAKTIFKAN
        |--------------------------------------------------------------------------
        */

        if ($request->boolean('aktif')) {

            AcademicSetting::query()
                ->where(
                    'id',
                    '!=',
                    $academicSetting->id
                )
                ->update([
                    'aktif' => 0,
                    'mode_ujian' => 0,
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | JIKA SEMESTER DINONAKTIFKAN
        | MODE UJIAN JUGA HARUS MATI
        |--------------------------------------------------------------------------
        */

        if (!$request->boolean('aktif')) {

            $academicSetting->mode_ujian = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $academicSetting->tahun_ajaran =
            $validated['tahun_ajaran'];

        $academicSetting->semester =
            $validated['semester'];

        $academicSetting->tanggal_mulai =
            $validated['tanggal_mulai'];

        $academicSetting->tanggal_selesai =
            $validated['tanggal_selesai'];

        $academicSetting->batas_paket_kelas_10 =
            $validated['batas_paket_kelas_10'];

        $academicSetting->batas_paket_kelas_11 =
            $validated['batas_paket_kelas_11'];

        $academicSetting->batas_paket_kelas_12 =
            $validated['batas_paket_kelas_12'];

        $academicSetting->aktif =
            $request->boolean('aktif');


        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        $academicSetting->save();


        return redirect()
            ->route('petugas.academic-settings.index')
            ->with(
                'success',
                'Pengaturan akademik berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | AKTIFKAN MODE UJIAN
    |--------------------------------------------------------------------------
    */

    public function aktifkanUjian($id)
    {
        $academicSetting =
            AcademicSetting::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | HANYA SEMESTER AKTIF YANG BOLEH MASUK MODE UJIAN
        |--------------------------------------------------------------------------
        */

        if ((int) $academicSetting->aktif !== 1) {

            return redirect()
                ->route(
                    'petugas.academic-settings.index'
                )
                ->with(
                    'error',
                    'Mode ujian hanya dapat diaktifkan pada semester yang sedang aktif.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | MATIKAN MODE UJIAN SEMESTER LAIN
        |--------------------------------------------------------------------------
        */

        AcademicSetting::query()
            ->where(
                'id',
                '!=',
                $academicSetting->id
            )
            ->update([
                'mode_ujian' => 0,
            ]);


        /*
        |--------------------------------------------------------------------------
        | AKTIFKAN MODE UJIAN
        |--------------------------------------------------------------------------
        */

        $academicSetting->update([
            'mode_ujian' => 1,
        ]);


        return redirect()
            ->route(
                'petugas.academic-settings.index'
            )
            ->with(
                'success',
                'Mode ujian Semester ' .
                $academicSetting->semester .
                ' berhasil diaktifkan. Siswa sekarang dapat meminjam seluruh buku paket pada semester tersebut.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | NONAKTIFKAN MODE UJIAN
    |--------------------------------------------------------------------------
    */

    public function nonaktifkanUjian($id)
    {
        $academicSetting =
            AcademicSetting::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | NONAKTIFKAN MODE UJIAN
        |--------------------------------------------------------------------------
        */

        $academicSetting->update([
            'mode_ujian' => 0,
        ]);


        return redirect()
            ->route(
                'petugas.academic-settings.index'
            )
            ->with(
                'success',
                'Mode ujian Semester ' .
                $academicSetting->semester .
                ' berhasil dinonaktifkan. Aturan peminjaman kembali menggunakan batas normal.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $academicSetting =
            AcademicSetting::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | JANGAN HAPUS SEMESTER AKTIF
        |--------------------------------------------------------------------------
        */

        if ((int) $academicSetting->aktif === 1) {

            return redirect()
                ->route(
                    'petugas.academic-settings.index'
                )
                ->with(
                    'error',
                    'Pengaturan akademik yang sedang aktif tidak dapat dihapus.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS
        |--------------------------------------------------------------------------
        */

        $academicSetting->delete();


        return redirect()
            ->route(
                'petugas.academic-settings.index'
            )
            ->with(
                'success',
                'Pengaturan akademik berhasil dihapus.'
            );
    }
}