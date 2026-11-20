<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaterialController extends Controller
{
    /**
     * Kategori energi standar sesuai kurikulum EBT dan SDG 7.
     */
    public const DEFAULT_CATEGORIES = [
        'Energi Surya',
        'Energi Angin',
        'Energi Air',
        'Hemat Energi',
        'Biomassa',
        'Geotermal',
    ];

    /**
     * Menampilkan daftar modul materi energi dengan pencarian, filter kategori, dan pengurutan (Modul F-06).
     *
     * Memenuhi:
     * - PBI-016: Parameter ?search= atau ?q= (pencarian kata kunci case-insensitive pada judul & isi/deskripsi materi)
     * - PBI-017: Parameter ?category= (filter kategori energi, dapat dikombinasikan dengan pencarian)
     * - PBI-018: Parameter ?sort= (pengurutan terbaru, terlama, judul A-Z, judul Z-A)
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = trim((string) $request->query('search', $request->query('q', '')));
        $category = trim((string) $request->query('category', ''));
        $sort = strtolower(trim((string) $request->query('sort', 'terbaru')));

        $query = Material::query();

        // 1. PBI-016: Pencarian kata kunci (case-insensitive) pada judul atau isi/deskripsi materi
        if ($search !== '') {
            $searchTerm = '%' . mb_strtolower($search, 'UTF-8') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                  ->orWhereRaw('LOWER(description) LIKE ?', [$searchTerm])
                  ->orWhereRaw('LOWER(body) LIKE ?', [$searchTerm]);
            });
        }

        // 2. PBI-017: Filter kategori energi (bisa dikombinasikan dengan pencarian)
        if ($category !== '' && strtolower($category) !== 'semua' && strtolower($category) !== 'all') {
            $normalizedCategory = str_replace('-', ' ', $category);
            $query->where(function ($q) use ($category, $normalizedCategory) {
                $q->where('category', $category)
                  ->orWhere('category', 'like', "%{$category}%")
                  ->orWhere('category', 'like', "%{$normalizedCategory}%");
            });
        }

        // 3. PBI-018: Pengurutan hasil materi
        switch ($sort) {
            case 'terlama':
            case 'oldest':
                $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
                $sort = 'terlama';
                break;

            case 'judul-az':
            case 'judul a-z':
            case 'title-asc':
            case 'title_asc':
            case 'a-z':
                $query->orderBy('title', 'asc')->orderBy('id', 'asc');
                $sort = 'judul-az';
                break;

            case 'judul-za':
            case 'judul z-a':
            case 'title-desc':
            case 'title_desc':
            case 'z-a':
                $query->orderBy('title', 'desc')->orderBy('id', 'desc');
                $sort = 'judul-za';
                break;

            case 'terbaru':
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
                $sort = 'terbaru';
                break;
        }

        $materials = $query->get();

        // Kategori dinamis dari database digabungkan dengan kategori standar
        $dbCategories = Material::select('category')->distinct()->pluck('category')->toArray();
        $categories = array_values(array_unique(array_merge(Material::CATEGORIES, $dbCategories)));

        // Respons API / JSON jika diminta via header Accept: application/json, AJAX, atau path /api/*
        if ($request->expectsJson() || $request->ajax() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $materials->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'category' => $item->category,
                        'description' => $item->description,
                        'body' => $item->body,
                        'image' => $item->image,
                        'created_at' => $item->created_at?->toISOString(),
                        'detail_url' => route('materials.show', $item->id),
                    ];
                }),
                'total' => $materials->count(),
                'filters' => [
                    'search' => $search,
                    'category' => $category,
                    'sort' => $sort,
                ],
                'categories' => $categories,
            ]);
        }

        return view('pages.materi-index', compact('materials', 'categories', 'search', 'category', 'sort'));
    }

    /**
     * Halaman daftar materi berdasarkan kategori spesifik.
     */
    public function category(string $category): View
    {
        $categoryName = str_replace('-', ' ', ucwords($category, '-'));
        $materials = Material::where('category', 'like', "%{$categoryName}%")->get();
        return view('pages.materi-category', compact('materials', 'categoryName'));
    }

    /**
     * Halaman detail satu materi dan kuis terkait.
     */
    public function show(int|string $id): View
    {
        $material = Material::with('quizzes')->findOrFail($id);
        return view('pages.materi-detail', compact('material'));
    }
}
