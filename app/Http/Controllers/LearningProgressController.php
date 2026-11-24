<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Progress;
use Illuminate\Support\Facades\Auth;

class LearningProgressController extends Controller
{
    /**
     * Menampilkan halaman Learning Progress mahasiswa yang sedang login.
     * Menampilkan seluruh materi beserta status penyelesaian per user.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil seluruh materi
        $materials = Material::orderBy('category')->orderBy('title')->get();

        // Ambil progress milik user yang login saja (key by material_id untuk lookup cepat)
        $userProgress = Progress::where('user_id', $user->id)
            ->get()
            ->keyBy('material_id');

        $totalMaterials = $materials->count();
        $completedCount = $userProgress->where('status', 'completed')->count();
        $notCompletedCount = $totalMaterials - $completedCount;
        $percentage = $totalMaterials > 0
            ? round(($completedCount / $totalMaterials) * 100)
            : 0;

        return view('pages.learning-progress', compact(
            'user',
            'materials',
            'userProgress',
            'totalMaterials',
            'completedCount',
            'notCompletedCount',
            'percentage'
        ));
    }

    /**
     * Menandai materi sebagai selesai (completed) untuk user yang login.
     */
    public function markComplete($materialId)
    {
        $user = Auth::user();

        // Pastikan materi ada
        $material = Material::findOrFail($materialId);

        Progress::updateOrCreate(
            [
                'user_id' => $user->id,
                'material_id' => $material->id,
            ],
            [
                'status' => 'completed',
            ]
        );

        return redirect()->route('learning-progress.index')
            ->with('success', 'Materi "' . $material->title . '" berhasil ditandai selesai!');
    }
}
