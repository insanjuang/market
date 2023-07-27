<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Models\Products\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Alert;

class CategoryController extends Controller
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
        return view('pages.products.category.index');
    }

    public function data()
    {
        $category = Category::Where('parent_code','')->orderBy('id_kategori', 'desc')->get();

        return datatables()
            ->of($category)
            ->addIndexColumn()
            ->addColumn('image', function ($category) {
                if (isset($category->image) || $category->image != "") {
                    return '<a class="product-img">
                                <img src="'.asset('storage/'.$this->imgUploadsPath.'/'.$category->image).'" alt="product">
                            </a>';
                } else {
                    return  '<a class="product-img">
                                <img src="'.asset('storage/'.$this->imgUploadsPath.'/product1.jpg').'" alt="product">
                            </a>';
                }
            })
            ->addColumn('action', function ($category) {
                return '
                        <a class="me-3" href="'.route('category.edit', $category->id_kategori).'">
                            <img src="'.asset('/assets/img/icons/edit.svg').'" alt="img">
                        </a>
                        <a class="me-3" onclick="deleteData(`'.route('category.destroy', $category->id_kategori).'`)">
                            <img src="'. asset('/assets/img/icons/delete.svg').'" alt="img">
                        </a>
                    ';
            })
            ->rawColumns(['image','action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.products.category.form');
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

        Alert::success('Success', 'Category created successfully');
        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        return response()->json($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.products.category.form',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'kode' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,svg|file|max:1024',
        ]);

        $input = $request->except(['oldImage']);

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

        $category->update($input);

        Alert::success('Success', 'Category updated successfully');
        return redirect()->route('category.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        return response(null, 204);
    }
}
