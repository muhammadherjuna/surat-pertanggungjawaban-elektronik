<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Spj;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SpjController extends Controller
{
    public function index()
    {
        $spjs = Spj::whereIn('status_level', [3, 4])->latest()->get();
        $stats = [
            'total_masuk' => Spj::where('status_level', 3)->count(),
            'total_selesai' => Spj::where('status_level', 4)->count(),
        ];
        return view('bendahara.spj.index', compact('spjs', 'stats'));
    }

    public function show(Spj $spj)
    {
        if ($spj->status_level < 3) abort(404);
        
        $spj->load(['jenisSpj.dokumenPendukungs', 'rekening', 'dokumens.dokumenPendukung']);
        return view('bendahara.spj.show', compact('spj'));
    }

    public function verify(Spj $spj)
    {
        if ($spj->status_level !== 3 || $spj->is_rejected) {
            return back()->with('error', 'SPJ ini tidak dapat diverifikasi.');
        }

        $spj->update(['status_level' => 4]);

        return redirect()->route('bendahara.spj.index')->with('success', 'SPJ berhasil diverifikasi dan diselesaikan.');
    }

    public function printPdf(Spj $spj)
    {
        if ($spj->status_level !== 4) {
            return back()->with('error', 'Hanya SPJ yang telah selesai diverifikasi yang dapat dicetak.');
        }

        $url = route('public.spj.verify', $spj->uuid);
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->generate($url));

        $pdf = Pdf::loadView('bendahara.spj.pdf', compact('spj', 'qrcode'));
        return $pdf->stream('SPJ-'.$spj->id.'.pdf');
    }
}
