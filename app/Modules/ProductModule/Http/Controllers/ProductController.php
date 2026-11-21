<?php

namespace App\Modules\ProductModule\Http\Controllers;

use App\Modules\ProductModule\Http\Requests\ProductRequest;
use App\Modules\ProductModule\Models\Category;
use App\Modules\ProductModule\Models\Product;
use App\Modules\ProductModule\Models\Unit;
use App\Modules\ProductModule\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class ProductController implements HasMiddleware
{
    public function __construct(private readonly ProductService $products)
    {
    }

    public static function middleware(): array
    {
        return [new Middleware('role:admin', except: ['index'])];
    }

    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['unit', 'categories'])
            ->search($request->string('q')->toString())
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('categories', fn ($categories) => $categories->whereKey($request->integer('category')));
            })
            ->when($request->boolean('low'), fn ($query) => $query->whereColumn('stock', '<=', 'min_stock'))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('product::products.index', [
            'products' => $products,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('product::products.create', $this->formData());
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = $this->products->create($request->validated());

        return redirect()->route('products.index')->with('success', "Barang {$product->name} berhasil ditambahkan.");
    }

    public function edit(Product $product): View
    {
        return view('product::products.edit', $this->formData() + ['product' => $product->load('categories')]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->products->update($product, $request->validated());

        return redirect()->route('products.index')->with('success', "Barang {$product->name} berhasil diperbarui.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->products->delete($product);

        return redirect()->route('products.index')->with('success', "Barang {$product->name} berhasil dihapus.");
    }

    private function formData(): array
    {
        return [
            'units' => Unit::query()->orderBy('name')->get(),
            'categories' => Category::query()->orderBy('name')->get(),
        ];
    }
}
