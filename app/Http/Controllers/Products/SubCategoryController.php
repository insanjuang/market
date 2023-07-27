<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Models\Products\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Alert;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $imgUploadsPath;

    public function __construct()
    {
        $this->imgUploadsPath = 'uploads/image/product/category';
    }

    public function index()
    {
        return view('pages.products.subcategory.index');
    }

    public function data()
    {
        $subCategory = Category::select('kategori.*','pk.nama_kategori as parent')
                    ->leftJoin('kategori as pk', 'kategori.parent_code','=','pk.kode')
                    ->where('kategori.parent_code','!=','')->orderBy('kategori.id_kategori', 'desc')->get();

        return datatables()
            ->of($subCategory)
            ->addIndexColumn()
            ->addColumn('image', function ($subCategory) {
                if (isset($subCategory->image) || $subCategory->image != "") {
                    return '<a class="product-img">
                                <img src="'.asset('storage/'.$this->imgUploadsPath.'/'.$subCategory->image).'" alt="product">
                            </a>';
                } else {
                    return  '<a class="product-img">
                                <img src="'.asset('storage/'.$this->imgUploadsPath.'/product1.jpg').'" alt="product">
                            </a>';
                }
            })
            ->addColumn('action', function ($subCategory) {
                return '
                        <a class="me-3" href="'.route('subcategory.edit', $subCategory->id_kategori).'">
                            <img src="'.asset('/assets/img/icons/edit.svg').'" alt="img">
                        </a>
                        <a class="me-3" onclick="deleteData(`'.route('subcategory.destroy', $subCategory->id_kategori).'`)">
                            <img src="'. asset('/assets/img/icons/delete.svg').'" alt="img">
                        </a>
                    ';
            })
            ->rawColumns(['image','action'])
            ->make(true);
    }

    public function dataJson(Request $request)
    {
        $search = $request->search;
        $parent = $request->string('parent_code');

        $subcategory = DB::table('kategori')->orderby('id_kategori','asc')->select('id_kategori','kode','nama_kategori')
            ->when($parent, function (Builder $query, string $parent) {
                $query->where('parent_code', $parent);
            })
            ->get();

        return response()->json($subcategory);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::Where('parent_code','')->orderBy('kode', 'asc')->get();
        return view('pages.products.subcategory.form', compact('category'));
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
            'nama_kategori' => 'required',
            'kode' => 'required|unique:kategori',
            'parent_code' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|file|max:1024',
        ]);

        $input = $request->except(['oldImage']);

        if ($image = $request->file('image')) {
            $profileImage = date('YmdHis') ."-".$input['kode']. "." . $image->getClientOriginalExtension();
            $image->storeAs($this->imgUploadsPath, $profileImage);
            $input['image'] = "$profileImage";
        }else{
            unset($input['image']);
        }

        Category::create($input);

        Alert::success('Success', 'SubCategory created successfully');
        return redirect()->route('subcategory.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subCategory = Category::find($id);

        return response()->json($subCategory);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::Where('parent_code','')->orderBy('kode', 'asc')->get();
        $subcategory = Category::findOrFail($id);
        return view('pages.products.subcategory.form',compact('subcategory','category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $subCategory)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'kode' => 'required',
            'parent_code' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|file|max:1024',
        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            if ($request->input('oldImage')) {
                Storage::delete($request->input('oldImage'));
            }
            $profileImage = date('YmdHis') ."-".$input['kode']. "." . $image->getClientOriginalExtension();
            $image->storeAs($this->imgUploadsPath, $profileImage);
            $input['image'] = "$profileImage";
        }else{
            unset($input['image']);
        }

        $subCategory->update($input);

        Alert::success('Success', 'SubCategory updated successfully');
        return redirect()->route('subcategory.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subCategory = Category::find($id);
        $subCategory->delete();

        return response(null, 204);
    }
}
