<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    public function index()
    {
        $rekenings = Rekening::all();
        return view('master.rekenings.index', compact('rekenings'));
    }

    public function create()
    {
        return view('master.rekenings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_rekening' => 'required|string|max:255|unique:rekenings|regex:/^[0-9.]+$/',
            'nama_rekening' => 'required|string|max:255',
        ], [
            'kode_rekening.regex' => 'Kode Rekening Anggaran hanya boleh berisi angka dan titik (misal: 5.1.02.01.01.0001).'
        ]);

        Rekening::create($request->all());

        return redirect()->route('master.rekenings.index')->with('success', 'Rekening berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Rekening $rekening)
    {
        return view('master.rekenings.edit', compact('rekening'));
    }

    public function update(Request $request, Rekening $rekening)
    {
        $request->validate([
            'kode_rekening' => 'required|string|max:255|regex:/^[0-9.]+$/|unique:rekenings,kode_rekening,' . $rekening->id,
            'nama_rekening' => 'required|string|max:255',
        ], [
            'kode_rekening.regex' => 'Kode Rekening Anggaran hanya boleh berisi angka dan titik (misal: 5.1.02.01.01.0001).'
        ]);

        $rekening->update($request->all());

        return redirect()->route('master.rekenings.index')->with('success', 'Rekening berhasil diperbarui.');
    }

    public function destroy(Rekening $rekening)
    {
        $rekening->delete();
        return redirect()->route('master.rekenings.index')->with('success', 'Rekening berhasil dihapus.');
    }
}
