<?php

namespace App\Http\Controllers;

use App\Models\JenisSpj;
use App\Models\DokumenPendukung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisSpjController extends Controller
{
    public function index()
    {
        $jenis_spjs = JenisSpj::with('dokumenPendukungs')->get();
        return view('master.jenis_spjs.index', compact('jenis_spjs'));
    }

    public function create()
    {
        return view('master.jenis_spjs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'dokumen' => 'nullable|array',
            'dokumen.*.nama_dokumen' => 'required|string|max:255',
            'dokumen.*.is_wajib' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $jenisSpj = JenisSpj::create(['nama_jenis' => $request->nama_jenis]);

            if ($request->has('dokumen') && is_array($request->dokumen)) {
                foreach ($request->dokumen as $doc) {
                    $jenisSpj->dokumenPendukungs()->create([
                        'nama_dokumen' => $doc['nama_dokumen'],
                        'is_wajib' => isset($doc['is_wajib']) ? $doc['is_wajib'] : false,
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('master.jenis-spjs.index')->with('success', 'Jenis SPJ dan Dokumen Pendukung berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(JenisSpj $jenis_spj)
    {
        $jenis_spj->load('dokumenPendukungs');
        return view('master.jenis_spjs.edit', compact('jenis_spj'));
    }

    public function update(Request $request, JenisSpj $jenis_spj)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
            'dokumen' => 'nullable|array',
            'dokumen.*.id' => 'nullable|exists:dokumen_pendukungs,id',
            'dokumen.*.nama_dokumen' => 'required|string|max:255',
            'dokumen.*.is_wajib' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $jenis_spj->update(['nama_jenis' => $request->nama_jenis]);


            $keepIds = [];
            if ($request->has('dokumen') && is_array($request->dokumen)) {
                foreach ($request->dokumen as $doc) {
                    if (isset($doc['id']) && $doc['id']) {
                        $dokumen = DokumenPendukung::find($doc['id']);
                        if ($dokumen && $dokumen->jenis_spj_id == $jenis_spj->id) {
                            $dokumen->update([
                                'nama_dokumen' => $doc['nama_dokumen'],
                                'is_wajib' => isset($doc['is_wajib']) ? $doc['is_wajib'] : false,
                            ]);
                            $keepIds[] = $dokumen->id;
                        }
                    } else {
                        $newDoc = $jenis_spj->dokumenPendukungs()->create([
                            'nama_dokumen' => $doc['nama_dokumen'],
                            'is_wajib' => isset($doc['is_wajib']) ? $doc['is_wajib'] : false,
                        ]);
                        $keepIds[] = $newDoc->id;
                    }
                }
            }


            $jenis_spj->dokumenPendukungs()->whereNotIn('id', $keepIds)->delete();

            DB::commit();
            return redirect()->route('master.jenis-spjs.index')->with('success', 'Jenis SPJ berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(JenisSpj $jenis_spj)
    {
        $jenis_spj->delete();
        return redirect()->route('master.jenis-spjs.index')->with('success', 'Jenis SPJ berhasil dihapus.');
    }
}
