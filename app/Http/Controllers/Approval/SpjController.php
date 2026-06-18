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
        $user = Auth::user();
        $targetLevel = $this->getTargetStatusLevel();
        
        // 1. Pending SPJs (Belum Disetujui)
        $pendingQuery = Spj::where('status_level', $targetLevel)
                           ->where('is_rejected', false);

        // 2. Approved SPJs History (Riwayat Persetujuan)
        $historyQuery = Spj::where('status_level', '>', $targetLevel)
                           ->where('is_rejected', false);

        // Filter by bidang if user is Kabid (level 1)
        if ($targetLevel == 1 && $user->bidang_id) {
            $pendingQuery->whereHas('user', function($q) use ($user) {
                $q->where('bidang_id', $user->bidang_id);
            });
            $historyQuery->whereHas('user', function($q) use ($user) {
                $q->where('bidang_id', $user->bidang_id);
            });
        }

        $pendingSpjs = $pendingQuery->latest('submitted_at')->latest()->get();
        $historySpjs = $historyQuery->latest('submitted_at')->latest()->get();

        return view('approval.spj.index', compact('pendingSpjs', 'historySpjs', 'targetLevel'));
    }

    public function show(Spj $spj)
    {
        $user = Auth::user();
        $targetLevel = $this->getTargetStatusLevel();
        
        // Security check: ensure SPJ status level is at or above the user's level
        if ($spj->status_level < $targetLevel || $spj->is_rejected) {
            abort(404);
        }
        
        // Security check: ensure Kabid can only see SPJs from their own bidang
        if ($targetLevel == 1 && $user->bidang_id && $spj->user->bidang_id !== $user->bidang_id) {
            abort(403, 'Anda tidak memiliki akses ke SPJ dari bidang lain.');
        }

        $spj->load(['jenisSpj.dokumenPendukungs', 'rekening', 'dokumens.dokumenPendukung']);
        return view('approval.spj.show', compact('spj', 'targetLevel'));
    }

    public function approve(Spj $spj)
    {
        $user = Auth::user();
        $targetLevel = $this->getTargetStatusLevel();
        
        if ($spj->status_level !== $targetLevel || $spj->is_rejected) {
            return back()->with('error', 'SPJ ini tidak dapat disetujui saat ini.');
        }

        if ($targetLevel == 1 && $user->bidang_id && $spj->user->bidang_id !== $user->bidang_id) {
            return back()->with('error', 'Anda tidak memiliki hak untuk menyetujui SPJ dari bidang lain.');
        }

        $spj->update(['status_level' => $spj->status_level + 1]);

        return redirect()->route('approval.spj.index')->with('success', 'SPJ berhasil disetujui.');
    }

    public function reject(Request $request, Spj $spj)
    {
        $user = Auth::user();
        $targetLevel = $this->getTargetStatusLevel();
        
        if ($spj->status_level !== $targetLevel || $spj->is_rejected) {
            return back()->with('error', 'SPJ ini tidak dapat ditolak saat ini.');
        }

        if ($targetLevel == 1 && $user->bidang_id && $spj->user->bidang_id !== $user->bidang_id) {
            return back()->with('error', 'Anda tidak memiliki hak untuk menolak SPJ dari bidang lain.');
        }

        $hasComment = false;
        if ($request->has('komentar') && is_array($request->komentar)) {
            foreach ($request->komentar as $komentarText) {
                if (!empty(trim($komentarText))) {
                    $hasComment = true;
                    break;
                }
            }
        }

        if (!$hasComment) {
            return back()->with('error', 'Gagal menolak SPJ. Silakan berikan alasan revisi pada minimal salah satu dokumen.');
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
