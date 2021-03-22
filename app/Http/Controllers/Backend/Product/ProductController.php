<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use App\Product;
use App\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return view('backend.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        $html = getProductCategory($parent_id = 0);
        return view('backend.product.create', compact('html', 'tags'));
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
            'name' => 'required',
            'desc' => 'required',
            'basePrice' => 'required'
        ],
            [
                'name.required' => 'Không được để trống',
                'desc.required' => 'Không được để trống',
                'basePrice.required' => 'Không được để trống',
            ]);


        $product = new Product();

        $product->name = $request->name;
        $product->description = $request->desc;
        $product->seller_id = auth()->user()->id;
        $product->base_price = $request->basePrice;
        $product->discount_price = $request->discountPrice;
        $product->category_id = $request->category;
        $product->save();

        $product->tags()->sync($request->tags);

        return redirect()->route('product');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $tags = Tag::all();
        $html = getProductCategory($product->category_id);
        return view('backend.product.edit', compact('html', 'product', 'tags'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'desc' => 'required',
            'basePrice' => 'required'
        ],
            [
                'name.required' => 'Không được để trống',
                'desc.required' => 'Không được để trống',
                'basePrice.required' => 'Không được để trống',
            ]);


        $product = Product::find($id);

        $product->name = $request->name;
        $product->description = $request->desc;
        $product->seller_id = auth()->user()->id;
        $product->base_price = $request->basePrice;
        $product->discount_price = $request->discountPrice;
        $product->category_id = $request->category;
        $product->save();

        $product->tags()->sync($request->tags);

        return redirect()->route('product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->tags()->detach();
        Product::destroy($id);

        return redirect()->route('product');
    }
}
