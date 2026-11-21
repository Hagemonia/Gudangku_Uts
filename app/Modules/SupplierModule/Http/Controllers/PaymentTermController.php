<?php

namespace App\Modules\SupplierModule\Http\Controllers;

use App\Modules\SupplierModule\Exceptions\PaymentTermInUseException;
use App\Modules\SupplierModule\Http\Requests\PaymentTermRequest;
use App\Modules\SupplierModule\Models\PaymentTerm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class PaymentTermController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('role:admin')];
    }

    public function index(): View
    {
        return view('supplier::payment-terms.index', [
            'terms' => PaymentTerm::query()->withCount('suppliers')->orderBy('days')->paginate(10),
        ]);
    }

    public function store(PaymentTermRequest $request): RedirectResponse
    {
        $term = PaymentTerm::query()->create($request->validated());

        return redirect()->route('payment-terms.index')->with('success', "Termin {$term->name} berhasil ditambahkan.");
    }

    public function edit(PaymentTerm $paymentTerm): View
    {
        return view('supplier::payment-terms.edit', ['term' => $paymentTerm]);
    }

    public function update(PaymentTermRequest $request, PaymentTerm $paymentTerm): RedirectResponse
    {
        $paymentTerm->update($request->validated());

        return redirect()->route('payment-terms.index')->with('success', "Termin {$paymentTerm->name} berhasil diperbarui.");
    }

    public function destroy(PaymentTerm $paymentTerm): RedirectResponse
    {
        if ($paymentTerm->suppliers()->withTrashed()->exists()) {
            throw new PaymentTermInUseException($paymentTerm->name);
        }

        $paymentTerm->delete();

        return redirect()->route('payment-terms.index')->with('success', "Termin {$paymentTerm->name} berhasil dihapus.");
    }
}
