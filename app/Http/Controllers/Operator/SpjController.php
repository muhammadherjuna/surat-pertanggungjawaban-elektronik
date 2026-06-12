<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Spj;
use App\Models\JenisSpj;
use App\Models\Rekening;
use App\Models\SpjDokumen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SpjController extends Controller
{
    public function index(Request $request)
    {
        $query = Spj::where('user_id', Auth::id());

        if ($request->has('status')) {
            $status = $request->status;
            if ($status == 'draft') {
                $query->where(function($q) {
                    $q->where('status_level', 0)
                      ->orWhere('is_rejected', true);
                });
            } elseif ($status == 'proses') {
                $query->whereBetween('status_level', [1, 3])
                      ->where('is_rejected', false);
            } elseif ($status == 'selesai') {
                $query->where('status_level', 4)
                      ->where('is_rejected', false);
            }
        }

        if ($request->filled('search')) {
            $query->where('deskripsi', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tipe')) {
            $query->where('filter_tipe', $request->tipe);
        }

        $spjs = $query->latest()->paginate(10)->withQueryString();
        return view('operator.spj.index', compact('spjs'));
    }

    public function create()
    {
        $jenisSpjs = JenisSpj::all();
        $rekenings = Rekening::all();
        return view('operator.spj.create', compact('jenisSpjs', 'rekenings'));
    }

    public function store(Request $request)
    {
        if ($request->has('nominal')) {
            $request->merge([
                'nominal' => str_replace('.', '', $request->nominal)
            ]);
        }

        $request->validate([
            'jenis_spj_id' => 'required|exists:jenis_spjs,id',
            'deskripsi' => 'required|string|max:255',
            'filter_tipe' => 'required|in:GU,TU',
            'filter_no' => 'nullable|integer',
            'nominal' => 'required|numeric|min:0',
            'rekening_id' => 'required|exists:rekenings,id',
        ]);

        $spj = Spj::create([
            'user_id' => Auth::id(),
            'jenis_spj_id' => $request->jenis_spj_id,
            'deskripsi' => $request->deskripsi,
            'filter_tipe' => $request->filter_tipe,
            'filter_no' => $request->filter_no,
            'nominal' => $request->nominal,
            'rekening_id' => $request->rekening_id,
            'status_level' => 0,
            'is_rejected' => false,
        ]);

        return redirect()->route('operator.spj.show', $spj)->with('success', 'SPJ berhasil dibuat. Silakan unggah dokumen pendukung.');
    }

    public function show(Spj $spj)
    {
        if ($spj->user_id !== Auth::id()) abort(403);

        $spj->load(['jenisSpj.dokumenPendukungs', 'rekening', 'dokumens.dokumenPendukung']);
        return view('operator.spj.show', compact('spj'));
    }

    public function edit(Spj $spj)
    {
        if ($spj->user_id !== Auth::id()) abort(403);
        if ($spj->status_level > 0 && !$spj->is_rejected) {
            return redirect()->route('operator.spj.index')->with('error', 'SPJ tidak dapat diedit karena sedang atau telah diproses.');
        }

        $jenisSpjs = JenisSpj::all();
        $rekenings = Rekening::all();
        return view('operator.spj.edit', compact('spj', 'jenisSpjs', 'rekenings'));
    }

    public function update(Request $request, Spj $spj)
    {
        if ($spj->user_id !== Auth::id()) abort(403);
        if ($spj->status_level > 0 && !$spj->is_rejected) {
            return redirect()->route('operator.spj.index')->with('error', 'SPJ tidak dapat diedit karena sedang atau telah diproses.');
        }

        if ($request->has('nominal')) {
            $request->merge([
                'nominal' => str_replace('.', '', $request->nominal)
            ]);
        }

        $request->validate([
            'jenis_spj_id' => 'required|exists:jenis_spjs,id',
            'deskripsi' => 'required|string|max:255',
            'filter_tipe' => 'required|in:GU,TU',
            'filter_no' => 'nullable|integer',
            'nominal' => 'required|numeric|min:0',
            'rekening_id' => 'required|exists:rekenings,id',
        ]);

        $spj->update([
            'jenis_spj_id' => $request->jenis_spj_id,
            'deskripsi' => $request->deskripsi,
            'filter_tipe' => $request->filter_tipe,
            'filter_no' => $request->filter_no,
            'nominal' => $request->nominal,
            'rekening_id' => $request->rekening_id,
            'status_level' => 0, // Reset status level to 0 if updated
            'is_rejected' => false, // Reset rejection status
        ]);

        // When SPJ is re-submitted after rejection, we might want to clear rejection marks
        // Wait, the requirement says whole SPJ is rejected, so resetting is_rejected and status_level to 0 is good, it will go through approval again.

        return redirect()->route('operator.spj.show', $spj)->with('success', 'SPJ berhasil diperbarui.');
    }

    public function destroy(Spj $spj)
    {
        if ($spj->user_id !== Auth::id()) abort(403);
        if ($spj->status_level > 0) {
            return redirect()->route('operator.spj.index')->with('error', 'SPJ tidak dapat dihapus karena sudah diproses.');
        }

        foreach($spj->dokumens as $dok) {
            if (Storage::disk('public')->exists($dok->file_path)) {
                Storage::disk('public')->delete($dok->file_path);
            }
        }
        $spj->delete();

        return redirect()->route('operator.spj.index')->with('success', 'SPJ berhasil dihapus.');
    }

    public function storeDokumen(Request $request, Spj $spj)
    {
        if ($spj->user_id !== Auth::id()) abort(403);
        if ($spj->status_level > 0 && !$spj->is_rejected) {
            return back()->with('error', 'Tidak dapat menambah dokumen pada SPJ yang sedang diproses.');
        }

        $request->validate([
            'dokumen_pendukung_id' => 'required|exists:dokumen_pendukungs,id',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('file')->store('spj_dokumens', 'public');

        // Check if there is existing document of the same type, update it
        $existing = $spj->dokumens()->where('dokumen_pendukung_id', $request->dokumen_pendukung_id)->first();
        if ($existing) {
            if (Storage::disk('public')->exists($existing->file_path)) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $existing->update([
                'file_path' => $path,
                'komentar_revisi' => null // clear comment since newly uploaded
            ]);
        } else {
            SpjDokumen::create([
                'spj_id' => $spj->id,
                'dokumen_pendukung_id' => $request->dokumen_pendukung_id,
                'file_path' => $path,
            ]);
        }
        
        // Every time a document is updated after rejection, we should probably reset SPJ to Level 0
        if ($spj->is_rejected) {
            $spj->update([
                'status_level' => 0,
                'is_rejected' => false
            ]);
        }

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function destroyDokumen(Spj $spj, SpjDokumen $dokumen)
    {
        if ($spj->user_id !== Auth::id()) abort(403);
        if ($spj->status_level > 0 && !$spj->is_rejected) {
            return back()->with('error', 'Tidak dapat menghapus dokumen pada SPJ yang sedang diproses.');
        }
        if ($dokumen->spj_id !== $spj->id) abort(404);

        if (Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function submit(Spj $spj)
    {
        if ($spj->user_id !== Auth::id()) abort(403);

        // Hanya bisa diajukan jika masih di draft (level 0) atau perlu revisi
        if ($spj->status_level > 0 && !$spj->is_rejected) {
            return redirect()->route('operator.spj.index')->with('error', 'SPJ ini sudah dalam proses persetujuan.');
        }

        // Cek apakah semua dokumen pendukung sudah diunggah
        $spj->load(['jenisSpj.dokumenPendukungs', 'dokumens']);
        $requiredDocs = $spj->jenisSpj->dokumenPendukungs;
        $uploadedDocIds = $spj->dokumens->pluck('dokumen_pendukung_id');

        $missingDocs = $requiredDocs->filter(fn($dp) => !$uploadedDocIds->contains($dp->id));

        if ($missingDocs->isNotEmpty()) {
            $missingNames = $missingDocs->pluck('nama_dokumen')->implode(', ');
            return redirect()->route('operator.spj.show', $spj)
                ->with('error', "Pengajuan gagal! Dokumen berikut belum diunggah: {$missingNames}.");
        }

        // Set status menjadi "Diajukan" (masih level 0, tapi tandai is_submitted / tandai tidak lagi rejected)
        $spj->update([
            'is_rejected' => false,
            // status_level tetap 0, tapi sudah "diajukan" ke Kabid untuk review
            // Sebenarnya kita set ke level 1 agar masuk antrian approval
            'status_level' => 1,
        ]);

        return redirect()->route('operator.spj.index')->with('success', "SPJ '{$spj->deskripsi}' berhasil diajukan dan menunggu persetujuan Kabid.");
    }
}

