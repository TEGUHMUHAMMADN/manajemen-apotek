<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        $categories = Category::withCount('medicines')->latest()->get();

        return Inertia::render('Categories/Index', [
            'categories' => $categories
        ]);
    }

    // Menyimpan kategori baru (dipakai untuk dropdown di form obat atau halaman kelola kategori)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Kategori obat berhasil ditambahkan!');
    }

    // Menghapus kategori
    public function destroy(Category $category)
    {
        // Cek apakah kategori masih dipakai oleh obat
        if ($category->medicines()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Kategori tidak dapat dihapus karena masih digunakan oleh data obat!']);
        }

        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}