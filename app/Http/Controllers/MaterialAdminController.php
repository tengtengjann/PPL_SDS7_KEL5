<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MaterialAdminController extends Controller
{
    /**
     * Tampilkan daftar seluruh materi untuk panel Organisasi SRE.
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));

        $query = Material::withCount('quizzes')->with('quizzes.questions');

        if ($search !== '') {
            $searchTerm = '%' . mb_strtolower($search, 'UTF-8') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                  ->orWhereRaw('LOWER(description) LIKE ?', [$searchTerm]);
            });
        }

        if ($category !== '' && strtolower($category) !== 'semua') {
            $query->where('category', $category);
        }

        $materials = $query->orderBy('created_at', 'desc')->get();
        $categories = Material::CATEGORIES;

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success'   => true,
                'data'      => $materials,
                'total'     => $materials->count(),
            ]);
        }

        return view('pages.admin.material-index', compact('materials', 'categories', 'search', 'category'));
    }

    /**
     * Tampilkan formulir tambah materi pembelajaran baru.
     */
    public function create(): View
    {
        return view('pages.admin.material-form', [
            'material'   => null,
            'isEditing'  => false,
            'categories' => Material::CATEGORIES,
        ]);
    }

    /**
     * Simpan materi pembelajaran baru (PBI-013).
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'body'        => 'required|string',
            'image'       => 'nullable|file|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ], [
            'title.required'       => 'Judul materi pembelajaran wajib diisi.',
            'category.required'    => 'Kategori energi wajib dipilih.',
            'description.required' => 'Deskripsi singkat materi wajib diisi.',
            'body.required'        => 'Isi konten teks materi pembelajaran wajib diisi.',
            'image.image'          => 'File yang diunggah harus berupa gambar yang valid (JPEG, PNG, JPG, WEBP, SVG).',
            'image.mimes'          => 'Format gambar harus bertipe: jpeg, png, jpg, webp, svg.',
            'image.max'            => 'Ukuran file gambar tidak boleh melebihi 2MB (2048 KB).',
        ]);

        $data = [
            'title'       => $validated['title'],
            'category'    => $validated['category'],
            'description' => $validated['description'],
            'body'        => $validated['body'],
        ];

        // Penanganan upload file gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('materials', 'public');
            $data['image'] = $imagePath;
        }

        $material = Material::create($data);

        // Buat kuis default terkait materi ini jika belum ada
        Quiz::firstOrCreate(
            ['material_id' => $material->id],
            ['title' => 'Kuis: ' . $material->title]
        );

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Materi pembelajaran "' . $material->title . '" berhasil ditambahkan.',
                'data'    => $material,
            ], 201);
        }

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi "' . $material->title . '" berhasil ditambahkan dan kini tersedia bagi Mahasiswa!');
    }

    /**
     * Tampilkan formulir edit materi (PBI-014).
     */
    public function edit(Material $material): View
    {
        return view('pages.admin.material-form', [
            'material'   => $material,
            'isEditing'  => true,
            'categories' => Material::CATEGORIES,
        ]);
    }

    /**
     * Perbarui data materi pembelajaran (PBI-014).
     */
    public function update(Request $request, Material $material): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'body'        => 'required|string',
            'image'       => 'nullable|file|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ], [
            'title.required'       => 'Judul materi pembelajaran wajib diisi.',
            'category.required'    => 'Kategori energi wajib dipilih.',
            'description.required' => 'Deskripsi singkat materi wajib diisi.',
            'body.required'        => 'Isi konten teks materi pembelajaran wajib diisi.',
            'image.image'          => 'File yang diunggah harus berupa gambar yang valid (JPEG, PNG, JPG, WEBP, SVG).',
            'image.mimes'          => 'Format gambar harus bertipe: jpeg, png, jpg, webp, svg.',
            'image.max'            => 'Ukuran file gambar tidak boleh melebihi 2MB (2048 KB).',
        ]);

        $data = [
            'title'       => $validated['title'],
            'category'    => $validated['category'],
            'description' => $validated['description'],
            'body'        => $validated['body'],
        ];

        // Penanganan upload pembaruan gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada dan tersimpan di disk public
            if ($material->image && Storage::disk('public')->exists($material->image)) {
                Storage::disk('public')->delete($material->image);
            }
            $imagePath = $request->file('image')->store('materials', 'public');
            $data['image'] = $imagePath;
        }

        $material->update($data);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Materi pembelajaran "' . $material->title . '" berhasil diperbarui.',
                'data'    => $material,
            ]);
        }

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi "' . $material->title . '" berhasil diperbarui!');
    }

    /**
     * Hapus materi pembelajaran (PBI-014).
     */
    public function destroy(Request $request, Material $material): RedirectResponse|JsonResponse
    {
        $title = $material->title;

        // Hapus file gambar jika tersimpan di disk public
        if ($material->image && Storage::disk('public')->exists($material->image)) {
            Storage::disk('public')->delete($material->image);
        }

        $material->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Materi pembelajaran "' . $title . '" berhasil dihapus.',
            ]);
        }

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi "' . $title . '" berhasil dihapus.');
    }
}
