<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Gallery;
use App\Models\Letter;
use App\Models\News;
use App\Models\Rt;
use App\Models\Rw;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            return $this->superAdminDashboard();
        }
        
        return $this->villageDashboard($user);
    }

    /**
     * Super admin dashboard with global statistics.
     */
    protected function superAdminDashboard()
    {
        $stats = [
            'total_villages' => Desa::count(),
            'active_villages' => Desa::active()->count(),
            'total_users' => User::count(),
            'total_citizens' => User::citizens()->count(),
            'total_letters' => Letter::count(),
            'pending_letters' => Letter::pending()->count(),
        ];

        $recentVillages = Desa::latest()->limit(5)->get();
        $villageStats = Desa::withCount(['users', 'letters'])->latest()->limit(10)->get();

        return Inertia::render('dashboard', [
            'user_role' => 'super_admin',
            'stats' => $stats,
            'recent_villages' => $recentVillages,
            'village_stats' => $villageStats,
        ]);
    }

    /**
     * Village-specific dashboard.
     */
    protected function villageDashboard(User $user)
    {
        $desaId = $user->desa_id;

        // Base statistics for the village
        $stats = [
            'total_citizens' => User::where('desa_id', $desaId)->citizens()->count(),
            'total_letters' => Letter::where('desa_id', $desaId)->count(),
            'pending_letters' => Letter::where('desa_id', $desaId)->pending()->count(),
            'completed_letters' => Letter::where('desa_id', $desaId)->completed()->count(),
            'total_news' => News::where('desa_id', $desaId)->count(),
            'published_news' => News::where('desa_id', $desaId)->published()->count(),
            'total_galleries' => Gallery::where('desa_id', $desaId)->count(),
        ];

        // Role-specific additional data
        $additionalData = [];

        switch ($user->role) {
            case 'admin_desa':
            case 'kepala_desa':
                $additionalData = [
                    'total_rws' => Rw::where('desa_id', $desaId)->count(),
                    'total_rts' => Rt::where('desa_id', $desaId)->count(),
                    'recent_letters' => Letter::where('desa_id', $desaId)
                        ->with(['citizen', 'letterType', 'rt', 'rw'])
                        ->latest()
                        ->limit(5)
                        ->get(),
                    'demographic_stats' => $this->getDemographicStats($desaId),
                    'letter_status_stats' => $this->getLetterStatusStats($desaId),
                ];
                break;

            case 'ketua_rw':
                $additionalData = [
                    'rw_citizens' => User::where('desa_id', $desaId)
                        ->where('rw_id', $user->rw_id)
                        ->citizens()
                        ->count(),
                    'rw_letters' => Letter::where('desa_id', $desaId)
                        ->where('rw_id', $user->rw_id)
                        ->count(),
                    'pending_rw_letters' => Letter::where('desa_id', $desaId)
                        ->where('rw_id', $user->rw_id)
                        ->whereIn('status', ['rt_approved'])
                        ->count(),
                ];
                break;

            case 'ketua_rt':
                $additionalData = [
                    'rt_citizens' => User::where('desa_id', $desaId)
                        ->where('rt_id', $user->rt_id)
                        ->citizens()
                        ->count(),
                    'rt_letters' => Letter::where('desa_id', $desaId)
                        ->where('rt_id', $user->rt_id)
                        ->count(),
                    'pending_rt_letters' => Letter::where('desa_id', $desaId)
                        ->where('rt_id', $user->rt_id)
                        ->where('status', 'draft')
                        ->count(),
                ];
                break;

            case 'warga':
                $additionalData = [
                    'my_letters' => Letter::where('citizen_id', $user->id)->count(),
                    'my_pending_letters' => Letter::where('citizen_id', $user->id)->pending()->count(),
                    'recent_news' => News::where('desa_id', $desaId)
                        ->published()
                        ->latest('published_at')
                        ->limit(3)
                        ->get(),
                ];
                break;
        }

        $stats = array_merge($stats, $additionalData);

        return Inertia::render('dashboard', [
            'user_role' => $user->role,
            'stats' => $stats,
            'user' => $user->load(['desa', 'rt', 'rw']),
        ]);
    }

    /**
     * Get demographic statistics for the village.
     */
    protected function getDemographicStats($desaId): array
    {
        $citizens = User::where('desa_id', $desaId)->citizens();

        return [
            'gender_stats' => [
                'male' => (clone $citizens)->where('gender', 'L')->count(),
                'female' => (clone $citizens)->where('gender', 'P')->count(),
            ],
            'age_groups' => [
                'children' => (clone $citizens)->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18')->count(),
                'adults' => (clone $citizens)->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 59')->count(),
                'elderly' => (clone $citizens)->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 60')->count(),
            ],
            'education_stats' => User::where('desa_id', $desaId)
                ->citizens()
                ->whereNotNull('education')
                ->selectRaw('education, COUNT(*) as count')
                ->groupBy('education')
                ->pluck('count', 'education')
                ->toArray(),
            'marital_status_stats' => User::where('desa_id', $desaId)
                ->citizens()
                ->whereNotNull('marital_status')
                ->selectRaw('marital_status, COUNT(*) as count')
                ->groupBy('marital_status')
                ->pluck('count', 'marital_status')
                ->toArray(),
        ];
    }

    /**
     * Get letter status statistics.
     */
    protected function getLetterStatusStats($desaId): array
    {
        return Letter::where('desa_id', $desaId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}