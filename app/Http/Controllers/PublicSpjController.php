<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spj;

class PublicSpjController extends Controller
{
    public function verify($uuid)
    {
        $spj = Spj::with(['user', 'jenisSpj.dokumenPendukungs', 'dokumens.dokumenPendukung'])->where('uuid', $uuid)->firstOrFail();
        
        return view('public.spj_verify', compact('spj'));
    }
}
