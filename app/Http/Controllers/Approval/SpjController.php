<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Spj;
use Illuminate\Support\Facades\Auth;

class SpjController extends Controller
{
    private function getTargetStatusLevel()
    {
        return Auth::user()->role->level;
    }

    public function index()
    {
        $targetLevel = $this->getTargetStatusLevel();
        $spjs = Spj::where('status_level', $targetLevel)
                   ->where('is_rejected', false)
                   ->latest()
                   ->get();

        return view('approval.spj.index', compact('spjs'));
    }

    public function show(Spj $spj)
    {
        $targetLevel = $this->getTargetStatusLevel();
        

        $spj->load(['jenisSpj.dokumenPendukungs', 'rekening', 'dokumens.dokumenPendukung']);
        return view('approval.spj.show', compact('spj', 'targetLevel'));
    }

    public function approve(Spj $spj)
    {
        $targetLevel = $this->getTargetStatusLevel();
        if ($spj->status_level !== $targetLevel || $spj->is_rejected) {
            return back()->with('error', 'SPJ ini tidak dapat disetujui saat ini.');
        }

        $spj->update(['status_level' => $spj->status_level + 1]);

        return redirect()->route('approval.spj.index')->with('success', 'SPJ berhasil disetujui.');
    }

    public function reject(Request $request, Spj $spj)
    {
        $targetLevel = $this->getTargetStatusLevel();
        if ($spj->status_level !== $targetLevel || $spj->is_rejected) {
            return back()->with('error', 'SPJ ini tidak dapat ditolak saat ini.');
        }


        if ($request->has('komentar') && is_array($request->komentar)) {
            foreach ($request->komentar as $dokumenId => $komentarText) {
                if (!empty($komentarText)) {
                    $dok = $spj->dokumens()->where('id', $dokumenId)->first();
                    if ($dok) {
                        $dok->update(['komentar_revisi' => $komentarText]);
                    }
                }
            }
        }

        $spj->update([
            'is_rejected' => true,
            'status_level' => 0
        ]);

        return redirect()->route('approval.spj.index')->with('success', 'SPJ berhasil ditolak dan dikembalikan ke operator.');
    }
}
