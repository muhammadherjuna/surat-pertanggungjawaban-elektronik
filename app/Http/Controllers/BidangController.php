<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::latest()->get();
        return view('master.bidangs.index', compact('bidangs'));
    }

    public function create()
    {
        return view('master.bidangs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
        ]);

        Bidang::create($request->all());

        return redirect()->route('master.bidangs.index')->with('success', 'Bidang berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $bidang = Bidang::findOrFail($id);
        return view('master.bidangs.edit', compact('bidang'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
        ]);

        $bidang = Bidang::findOrFail($id);
        $bidang->update($request->all());

        return redirect()->route('master.bidangs.index')->with('success', 'Bidang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $bidang = Bidang::findOrFail($id);
        $bidang->delete();

        return redirect()->route('master.bidangs.index')->with('success', 'Bidang berhasil dihapus.');
    }
}
