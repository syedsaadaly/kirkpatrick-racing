<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UnitRepository;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected $repository;

    public function __construct(UnitRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $units = $this->repository->all();
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:units,name',
            'short_name' => 'required'
        ]);

        $this->repository->create($request->all());
        return redirect()->route('admin.units.index')->with('success', 'Unit Created');
    }

    public function edit($id)
    {
        $unit = $this->repository->find($id);
        return view('admin.units.create', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:units,name,' . $id,
            'short_name' => 'required'
        ]);

        $this->repository->update($request->all(), $id);
        return redirect()->route('admin.units.index')->with('success', 'Unit Updated');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect()->route('admin.units.index')->with('success', 'Unit Deleted');
    }
}
