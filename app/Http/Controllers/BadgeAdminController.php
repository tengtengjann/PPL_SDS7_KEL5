<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeAdminController extends Controller
{
    /**
     * Daftar semua badge untuk panel admin.
     */
    public function index()
    {
        $badges = Badge::orderBy('criteria_value', 'asc')->get();
        return view('pages.admin.badge-index', compact('badges'));
    }

    /**
     * Form tambah badge baru.
     */
    public function create()
    {
        return view('pages.admin.badge-form', [
            'badge'      => null,
            'isEditing'  => false,
        ]);
    }

    /**
     * Simpan badge baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'icon'           => 'required|string|max:50',
            'criteria_type'  => 'required|in:modules_completed,quizzes_passed,xp_reached,missions_completed',
            'criteria_value' => 'required|integer|min:1',
        ]);

        Badge::create($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Form edit badge.
     */
    public function edit(Badge $badge)
    {
        return view('pages.admin.badge-form', [
            'badge'      => $badge,
            'isEditing'  => true,
        ]);
    }

    /**
     * Update data badge.
     */
    public function update(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'icon'           => 'required|string|max:50',
            'criteria_type'  => 'required|in:modules_completed,quizzes_passed,xp_reached,missions_completed',
            'criteria_value' => 'required|integer|min:1',
        ]);

        $badge->update($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge "' . $badge->name . '" berhasil diperbarui!');
    }

    /**
     * Hapus badge.
     */
    public function destroy(Badge $badge)
    {
        $name = $badge->name;
        $badge->delete();

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge "' . $name . '" berhasil dihapus.');
    }
}
