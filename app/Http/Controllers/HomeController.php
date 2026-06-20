<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spj;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        $latestSpjs = collect();
        
        if ($roleLevel == 0) {
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
                ->where('status_level', 5)
                ->where('is_rejected', false)
                ->count();

            $stats['total_nominal_selesai'] = (clone $query)
                ->where('status_level', 5)
                ->where('is_rejected', false)
                ->sum('nominal');
                
            $latestSpjs = Spj::where('user_id', $user->id)->latest('submitted_at')->latest()->take(10)->get();
            
        } elseif (in_array($roleLevel, [1, 2, 3])) {
            $targetLevel = $roleLevel;
            
            $pendingQuery = Spj::where('status_level', $targetLevel)->where('is_rejected', false);
            $approvedQuery = Spj::where('status_level', '>', $targetLevel)->where('is_rejected', false);
            $allVisibleQuery = Spj::where('status_level', '>=', $targetLevel)->where('is_rejected', false);

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
            
            $stats['perlu_tindakan'] = (clone $pendingQuery)->count();
            $stats['menunggu_verifikasi'] = (clone $approvedQuery)->count();
            $stats['selesai'] = (clone $pendingQuery)->sum('nominal');
            $stats['total_nominal_selesai'] = (clone $approvedQuery)->sum('nominal');
            
            $latestSpjs = $allVisibleQuery->latest('submitted_at')->latest()->take(10)->get();
            
        } elseif ($roleLevel == 4) {
            $pendingQuery = Spj::where('status_level', 4)->where('is_rejected', false);
            $verifiedQuery = Spj::where('status_level', 5)->where('is_rejected', false);
            $allVisibleQuery = Spj::whereIn('status_level', [4, 5])->where('is_rejected', false);
            
            $stats['perlu_tindakan'] = $pendingQuery->count();
            $stats['menunggu_verifikasi'] = $verifiedQuery->count();
            $stats['selesai'] = $pendingQuery->sum('nominal');
            $stats['total_nominal_selesai'] = $verifiedQuery->sum('nominal');
            
            $latestSpjs = $allVisibleQuery->latest('submitted_at')->latest()->take(10)->get();
            
        } else {
            // Super Admin Statistics (Technical & Master Data)
            $stats['total_users'] = \App\Models\User::count();
            $stats['total_bidangs'] = \App\Models\Bidang::count();
            $stats['total_rekenings'] = \App\Models\Rekening::count();
            $stats['total_jenis_spjs'] = \App\Models\JenisSpj::count();
            
            $latestUsers = \App\Models\User::with(['role', 'bidang'])->latest()->take(10)->get();
            $latestSpjs = collect();
        }

        return view('home', compact('stats', 'latestSpjs', 'latestUsers', 'roleLevel'));
    }
}
