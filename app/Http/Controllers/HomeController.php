<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spj;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'perlu_tindakan' => 0,
            'menunggu_verifikasi' => 0,
            'selesai' => 0,
            'total_nominal_selesai' => 0,
        ];

        // Untuk saat ini kita buat universal berdasarkan scope dokumen si User
        // Jika Operator, dia hanya melihat SPJ miliknya.
        // Jika Verifikator, nanti query disesuaikan (untuk saat ini fokus pada Operator UI)
        $query = Spj::where('user_id', $user->id);

        $stats['perlu_tindakan'] = (clone $query)
            ->where(function($q) {
                $q->where('status_level', 0)
                  ->orWhere('is_rejected', true);
            })->count();

        $stats['menunggu_verifikasi'] = (clone $query)
            ->whereBetween('status_level', [1, 3])
            ->where('is_rejected', false)
            ->count();

        $stats['selesai'] = (clone $query)
            ->where('status_level', 4)
            ->where('is_rejected', false)
            ->count();

        $stats['total_nominal_selesai'] = (clone $query)
            ->where('status_level', 4)
            ->where('is_rejected', false)
            ->sum('nominal');

        return view('home', compact('stats'));
    }
}
