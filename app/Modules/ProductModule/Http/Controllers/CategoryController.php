<?php

namespace App\Modules\ProductModule\Http\Controllers;

use App\Modules\ProductModule\Exceptions\ResourceInUseException;
use App\Modules\ProductModule\Http\Requests\CategoryRequest;
use App\Modules\ProductModule\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class CategoryController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('role:admin')];
    }

    public function index(): View
    {
        return view('product::categories.index', [
            'categories' => Category::query()->withCount('products')->orderBy('name')->paginate(10),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = Category::query()->create($request->validated());

        return redirect()->route('categories.index')->with('success', "Kategori {$category->name} berhasil ditambahkan.");
    }

    public function edit(Category $category): View
    {
        return view('product::categories.edit', ['category' => $category]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')->with('success', "Kategori {$category->name} berhasil diperbarui.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            throw new ResourceInUseException("Kategori {$category->name} masih dipakai oleh barang dan tidak bisa dihapus.");
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', "Kategori {$category->name} berhasil dihapus.");
    }
}
