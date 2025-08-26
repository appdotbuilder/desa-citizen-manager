<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCitizenRequest;
use App\Http\Requests\UpdateCitizenRequest;
use App\Models\Desa;
use App\Models\Rt;
use App\Models\Rw;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CitizenController extends Controller
{
    /**
     * Display a listing of citizens.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Build base query with tenant isolation
        $query = User::where('desa_id', $user->desa_id)
                    ->citizens()
                    ->with(['rt', 'rw', 'desa']);

        // Apply role-based filtering
        if ($user->isKetuaRt()) {
            $query->where('rt_id', $user->rt_id);
        } elseif ($user->isKetuaRw()) {
            $query->where('rw_id', $user->rw_id);
        }

        // Apply search filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('rt_id')) {
            $query->where('rt_id', $request->get('rt_id'));
        }

        if ($request->filled('rw_id')) {
            $query->where('rw_id', $request->get('rw_id'));
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->get('gender'));
        }

        if ($request->filled('citizen_status')) {
            $query->where('citizen_status', $request->get('citizen_status'));
        }

        $citizens = $query->latest()->paginate(15);

        // Get filter options
        $filterOptions = [
            'rts' => Rt::where('desa_id', $user->desa_id)->get(['id', 'number', 'rw_id']),
            'rws' => Rw::where('desa_id', $user->desa_id)->get(['id', 'number']),
        ];

        return Inertia::render('citizens/index', [
            'citizens' => $citizens,
            'filters' => $request->only(['search', 'rt_id', 'rw_id', 'gender', 'citizen_status']),
            'filter_options' => $filterOptions,
        ]);
    }

    /**
     * Show the form for creating a new citizen.
     */
    public function create()
    {
        $user = Auth::user();
        
        $formOptions = [
            'rws' => Rw::where('desa_id', $user->desa_id)->with('rts')->get(),
            'religions' => [
                'islam' => 'Islam',
                'kristen' => 'Kristen Protestan',
                'katolik' => 'Kristen Katolik',
                'hindu' => 'Hindu',
                'buddha' => 'Buddha',
                'konghucu' => 'Konghucu',
            ],
            'education_levels' => [
                'tidak_sekolah' => 'Tidak Sekolah',
                'sd' => 'SD',
                'smp' => 'SMP',
                'sma' => 'SMA',
                'diploma' => 'Diploma',
                'sarjana' => 'Sarjana',
                'magister' => 'Magister',
                'doktor' => 'Doktor',
            ],
            'marital_statuses' => [
                'belum_kawin' => 'Belum Kawin',
                'kawin' => 'Kawin',
                'cerai_hidup' => 'Cerai Hidup',
                'cerai_mati' => 'Cerai Mati',
            ],
        ];

        return Inertia::render('citizens/create', [
            'form_options' => $formOptions,
        ]);
    }

    /**
     * Store a newly created citizen.
     */
    public function store(StoreCitizenRequest $request)
    {
        $validated = $request->validated();
        $validated['desa_id'] = Auth::user()->desa_id;
        $validated['role'] = 'warga';
        $validated['password'] = Hash::make($validated['password'] ?? 'defaultpassword123');

        $citizen = User::create($validated);

        return redirect()->route('citizens.show', $citizen)
            ->with('success', 'Data warga berhasil ditambahkan.');
    }

    /**
     * Display the specified citizen.
     */
    public function show(User $citizen)
    {
        // Ensure citizen belongs to same village
        if ($citizen->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }

        $citizen->load(['desa', 'rt.rw', 'rw']);
        
        // Get citizen's recent letters
        $recentLetters = $citizen->letters()
            ->with(['letterType', 'rt', 'rw'])
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('citizens/show', [
            'citizen' => $citizen,
            'recent_letters' => $recentLetters,
        ]);
    }

    /**
     * Show the form for editing the specified citizen.
     */
    public function edit(User $citizen)
    {
        // Ensure citizen belongs to same village
        if ($citizen->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }

        $user = Auth::user();
        
        $formOptions = [
            'rws' => Rw::where('desa_id', $user->desa_id)->with('rts')->get(),
            'religions' => [
                'islam' => 'Islam',
                'kristen' => 'Kristen Protestan',
                'katolik' => 'Kristen Katolik',
                'hindu' => 'Hindu',
                'buddha' => 'Buddha',
                'konghucu' => 'Konghucu',
            ],
            'education_levels' => [
                'tidak_sekolah' => 'Tidak Sekolah',
                'sd' => 'SD',
                'smp' => 'SMP',
                'sma' => 'SMA',
                'diploma' => 'Diploma',
                'sarjana' => 'Sarjana',
                'magister' => 'Magister',
                'doktor' => 'Doktor',
            ],
            'marital_statuses' => [
                'belum_kawin' => 'Belum Kawin',
                'kawin' => 'Kawin',
                'cerai_hidup' => 'Cerai Hidup',
                'cerai_mati' => 'Cerai Mati',
            ],
        ];

        return Inertia::render('citizens/edit', [
            'citizen' => $citizen,
            'form_options' => $formOptions,
        ]);
    }

    /**
     * Update the specified citizen.
     */
    public function update(UpdateCitizenRequest $request, User $citizen)
    {
        // Ensure citizen belongs to same village
        if ($citizen->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }

        $validated = $request->validated();
        
        // Don't update password if not provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $citizen->update($validated);

        return redirect()->route('citizens.show', $citizen)
            ->with('success', 'Data warga berhasil diperbarui.');
    }

    /**
     * Remove the specified citizen.
     */
    public function destroy(User $citizen)
    {
        // Ensure citizen belongs to same village
        if ($citizen->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }

        // Check if citizen has pending letters
        if ($citizen->letters()->whereNotIn('status', ['selesai', 'rejected'])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus warga yang masih memiliki surat dalam proses.');
        }

        // Soft delete by changing status
        $citizen->update(['citizen_status' => 'inactive']);

        return redirect()->route('citizens.index')
            ->with('success', 'Data warga berhasil dinonaktifkan.');
    }
}