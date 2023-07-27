<?php

namespace App\Http\Controllers\Entities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Entities\Wilayah;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class WilayahController extends Controller
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
        //
    }

    public function data()
    {
        // $supplier = Wilayah::orderBy('nama', 'asc')->get();

        // return datatables()
        //     ->of($supplier)
        //     ->addIndexColumn()
        //     ->addColumn('action', function ($supplier) {
        //         return '
        //                 <a class="me-3" href="'.route('supplier.edit', $supplier->id_supplier).'">
        //                     <img src="'.asset('/assets/img/icons/edit.svg').'" alt="img">
        //                 </a>
        //                 <a class="me-3" onclick="deleteData(`'.route('supplier.destroy', $supplier->id_supplier).'`)">
        //                     <img src="'. asset('/assets/img/icons/delete.svg').'" alt="img">
        //                 </a>
        //             ';
        //     })
        //     ->rawColumns(['action'])
        //     ->make(true);
    }

    public function dataJson($tipe, Request $request)
    {
        $search = $request->search;
        $parent = $request->string('parent');

        $wilayah = DB::table('wilayah')->orderby('kode','asc')->select('kode','nama')
            ->when($tipe, function (Builder $query, string $tipe) {
                $query->where('tipe', $tipe);
            })
            ->when($parent, function (Builder $query, string $parent) {
                $query->where('parent_kode', $parent);
            })
            ->when($search, function (Builder $query, string $search) {
                $query->where('name', 'like', '%' .$search . '%');
            })
            ->get();

        // $response = array();
        // foreach($wilayah as $employee){
        //     $response[] = array(
        //         "id"=>$employee->id,
        //         "text"=>$employee->name
        //     );
        // }
        return response()->json($wilayah);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'nama' => 'required|unique:supplier',
        //     'alamat' => 'required',
        //     'telepon' => 'required|unique:supplier',
        // ]);

        // $input = $request->all();

        // Supplier::create($input);

        // Alert::success('Success', 'Supplier created successfully');
        // return redirect()->route('supplier.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $supplier = Supplier::find($id);

        // return response()->json($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $supplier = Supplier::findOrFail($id);
        // return view('pages.entities.supplier.form',compact('supplier'));
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
        // $request->validate([
        //     'nama' => 'required',
        //     'alamat' => 'required',
        //     'telepon' => 'required',
        //     'email' => 'required',
        // ]);

        // $input = $request->all();

        // $supplier->update($input);

        // Alert::success('Success', 'Supplier updated successfully');
        // return redirect()->route('supplier.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $supplier = Supplier::find($id);
        // $supplier->delete();

        // return response(null, 204);
    }
}
