<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCafeTableRequest;
use App\Http\Requests\UpdateCafeTableRequest;
use App\Models\CafeTable;

class CafeTableController extends Controller
{
    public function index()
    {
        $tables = CafeTable::latest()->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('admin.tables.create');
    }

    public function store(StoreCafeTableRequest $request)
    {
        CafeTable::create($request->validated());
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(CafeTable $cafeTable)
    {
        return view('admin.tables.edit', ['table' => $cafeTable]);
    }

    public function update(UpdateCafeTableRequest $request, CafeTable $cafeTable)
    {
        $cafeTable->update($request->validated());
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(CafeTable $cafeTable)
    {
        $cafeTable->delete();
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil dihapus.');
    }
}
