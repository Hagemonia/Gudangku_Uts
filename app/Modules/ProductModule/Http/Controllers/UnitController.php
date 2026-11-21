<?php

namespace App\Modules\ProductModule\Http\Controllers;

use App\Modules\ProductModule\Exceptions\ResourceInUseException;
use App\Modules\ProductModule\Http\Requests\UnitRequest;
use App\Modules\ProductModule\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class UnitController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('role:admin')];
    }

    public function index(): View
    {
        return view('product::units.index', [
            'units' => Unit::query()->withCount('products')->orderBy('name')->paginate(10),
        ]);
    }

    public function store(UnitRequest $request): RedirectResponse
    {
        $unit = Unit::query()->create($request->validated());

        return redirect()->route('units.index')->with('success', "Satuan {$unit->name} berhasil ditambahkan.");
    }

    public function edit(Unit $unit): View
    {
        return view('product::units.edit', ['unit' => $unit]);
    }

    public function update(UnitRequest $request, Unit $unit): RedirectResponse
    {
        $unit->update($request->validated());

        return redirect()->route('units.index')->with('success', "Satuan {$unit->name} berhasil diperbarui.");
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        if ($unit->products()->withTrashed()->exists()) {
            throw new ResourceInUseException("Satuan {$unit->name} masih dipakai oleh barang dan tidak bisa dihapus.");
        }

        $unit->delete();

        return redirect()->route('units.index')->with('success', "Satuan {$unit->name} berhasil dihapus.");
    }
}
