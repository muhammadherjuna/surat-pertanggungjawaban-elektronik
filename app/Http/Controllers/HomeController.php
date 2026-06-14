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
        $roleLevel = $user->role->level;
        
        $stats = [
            'perlu_tindakan' => 0,
            'menunggu_verifikasi' => 0,
            'selesai' => 0,
            'total_nominal_selesai' => 0,
        ];

        // $latestSpjs = ALL recent SPJ (monitoring log, broader scope)
        // $pendingSpjs = only those awaiting YOUR action (for inbox tabel)
        $latestSpjs = collect();
        
        if ($roleLevel == 0) {
            // Operator: Only see their own SPJs
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
                
            // Log aktivitas: ALL own SPJs (draft, proses, selesai)
            $latestSpjs = Spj::where('user_id', $user->id)->latest('submitted_at')->latest()->take(10)->get();
            
        } elseif (in_array($roleLevel, [1, 2, 3])) {
            // Approval (Kabid, Sekdin, Kadin)
            $targetLevel = $roleLevel;
            
            $pendingQuery = Spj::where('status_level', $targetLevel)->where('is_rejected', false);
            $approvedQuery = Spj::where('status_level', '>', $targetLevel)->where('is_rejected', false);
            
            // Build a base query for ALL SPJs visible to this approver (bidang-filtered)
            $allVisibleQuery = Spj::where('status_level', '>=', $targetLevel)->where('is_rejected', false);

            // Filter by bidang for Kabid (role level 1)
            if ($roleLevel == 1 && $user->bidang_id) {
                $pendingQuery->whereHas('user', function($q) use ($user) {
                    $q->where('bidang_id', $user->bidang_id);
                });
                $approvedQuery->whereHas('user', function($q) use ($user) {
                    $q->where('bidang_id', $user->bidang_id);
                });
                $allVisibleQuery->whereHas('user', function($q) use ($user) {
                    $q->where('bidang_id', $user->bidang_id);
                });
            }
            
            $stats['perlu_tindakan'] = (clone $pendingQuery)->count(); // Menunggu Persetujuan Anda
            $stats['menunggu_verifikasi'] = (clone $approvedQuery)->count(); // Telah Anda Setujui
            $stats['selesai'] = (clone $pendingQuery)->sum('nominal'); // Total Nominal Menunggu
            $stats['total_nominal_selesai'] = (clone $approvedQuery)->sum('nominal'); // Total Nominal Disetujui
            
            // Log aktivitas: ALL SPJ in scope (pending + already approved onward) for monitoring
            $latestSpjs = $allVisibleQuery->latest('submitted_at')->latest()->take(10)->get();
            
        } elseif ($roleLevel == 4) {
            // Bendahara
            $pendingQuery = Spj::where('status_level', 3)->where('is_rejected', false);
            $verifiedQuery = Spj::where('status_level', 4)->where('is_rejected', false);
            $allVisibleQuery = Spj::whereIn('status_level', [3, 4])->where('is_rejected', false);
            
            $stats['perlu_tindakan'] = $pendingQuery->count(); // Menunggu Verifikasi Anda
            $stats['menunggu_verifikasi'] = $verifiedQuery->count(); // Selesai / Terverifikasi
            $stats['selesai'] = $pendingQuery->sum('nominal'); // Total Nominal Menunggu
            $stats['total_nominal_selesai'] = $verifiedQuery->sum('nominal'); // Total Nominal Selesai
            
            // Log aktivitas: ALL SPJ in scope (pending + verified) for monitoring
            $latestSpjs = $allVisibleQuery->latest('submitted_at')->latest()->take(10)->get();
            
        } else {
            // Super Admin
            $stats['perlu_tindakan'] = \App\Models\User::count(); // Total Users
            $stats['menunggu_verifikasi'] = \App\Models\Bidang::count(); // Total Bidangs
            $stats['selesai'] = Spj::count(); // Total SPJ
            $stats['total_nominal_selesai'] = Spj::sum('nominal'); // Total Nominal
            
            $latestSpjs = Spj::latest()->take(10)->get();
        }

        return view('home', compact('stats', 'latestSpjs', 'roleLevel'));
    }
}
