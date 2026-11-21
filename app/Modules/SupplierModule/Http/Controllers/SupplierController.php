<?php

namespace App\Modules\SupplierModule\Http\Controllers;

use App\Modules\SupplierModule\Http\Requests\SupplierRequest;
use App\Modules\SupplierModule\Models\PaymentTerm;
use App\Modules\SupplierModule\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class SupplierController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('role:admin', except: ['index'])];
    }

    public function index(Request $request): View
    {
        $suppliers = Supplier::query()
            ->with('paymentTerm')
            ->search($request->string('q')->toString())
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('supplier::suppliers.index', ['suppliers' => $suppliers]);
    }

    public function create(): View
    {
        return view('supplier::suppliers.create', ['terms' => PaymentTerm::query()->orderBy('days')->get()]);
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        $supplier = Supplier::query()->create($request->validated());

        return redirect()->route('suppliers.index')->with('success', "Pemasok {$supplier->name} berhasil ditambahkan.");
    }

    public function edit(Supplier $supplier): View
    {
        return view('supplier::suppliers.edit', [
            'supplier' => $supplier,
            'terms' => PaymentTerm::query()->orderBy('days')->get(),
        ]);
    }

    public function update(SupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validated());

        return redirect()->route('suppliers.index')->with('success', "Pemasok {$supplier->name} berhasil diperbarui.");
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', "Pemasok {$supplier->name} berhasil dihapus.");
    }
}
