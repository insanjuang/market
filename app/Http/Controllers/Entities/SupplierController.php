<?php

namespace App\Http\Controllers\Entities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Entities\Supplier;
use Alert;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //
    }

    public function index()
    {
        return view('pages.entities.supplier.index');
    }

    public function data()
    {
        $supplier = Supplier::orderBy('nama', 'asc')->get();

        return datatables()
            ->of($supplier)
            ->addIndexColumn()
            ->addColumn('action', function ($supplier) {
                return '
                        <a class="me-3" href="'.route('supplier.edit', $supplier->id_supplier).'">
                            <img src="'.asset('/assets/img/icons/edit.svg').'" alt="img">
                        </a>
                        <a class="me-3" onclick="deleteData(`'.route('supplier.destroy', $supplier->id_supplier).'`)">
                            <img src="'. asset('/assets/img/icons/delete.svg').'" alt="img">
                        </a>
                    ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.entities.supplier.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:supplier',
            'alamat' => 'required',
            'telepon' => 'required|unique:supplier',
        ]);

        $input = $request->all();

        Supplier::create($input);

        Alert::success('Success', 'Supplier created successfully');
        return redirect()->route('supplier.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::find($id);

        return response()->json($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('pages.entities.supplier.form',compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'email' => 'required',
        ]);

        $input = $request->all();

        $supplier->update($input);

        Alert::success('Success', 'Supplier updated successfully');
        return redirect()->route('supplier.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();

        return response(null, 204);
    }
}
