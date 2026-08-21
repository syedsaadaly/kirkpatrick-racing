<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\VendorRepository;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    protected $repository;

    public function __construct(VendorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $vendors = $this->repository->all();
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:vendors,name',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'business_name' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('vendors/logos', 'public');
        }

        $this->repository->create($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'Vendor Created');
    }

    public function edit($id)
    {
        $vendor = $this->repository->find($id);
        return view('admin.vendors.create', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:vendors,name,' . $id,
            'email' => 'nullable|email',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('vendors/logos', 'public');
        }

        $this->repository->update($data, $id);

        return redirect()->route('admin.suppliers.index')->with('success', 'Vendor Updated');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect()->route('admin.suppliers.index')->with('success', 'Vendor Deleted');
    }
}
