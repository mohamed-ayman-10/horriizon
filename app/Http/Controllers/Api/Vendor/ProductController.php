<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Image;
use App\Models\Product;
use App\Traits\GeneralApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function show()
    {
        $products = Product::query()->where('vendor_id', Auth::guard('vendor_api')->user()->id)->get();
        return GeneralApi::returnData(200, 'Successfully', $products);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|min:2',
            'title_en' => 'required|string|min:2',
            'start_date' => 'required|string|date|min:2',
            'end_date' => 'required|string|date|min:2',
            'quantity' => 'required|string',
            'price' => 'required|string',
            'category_id' => 'required',
            'images' => 'required'
        ]);
        if ($validator->fails()) {
            return GeneralApi::response()->json($validator->errors(), 422);
        }

        $product = new Product();
        $product->title = [
            'ar' => $request->title_ar,
            'en' => $request->title_en,
        ];
        $product->start_date = $request->start_date;
        $product->end_date = $request->end_date;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->vendor_id = Auth::guard('vendor_api')->user()->id;
        $product->save();

        if ($request->file('images')) {
            foreach ($request->images as $file) {
                $image = new Image();
                $image->product_id = $product->id;
                $image->path = Storage::disk('uploadFile')->put('products', $file);
                $image->save();
            }
        }
        return GeneralApi::returnData(201, 'Create Successfully', $product);

    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|min:2',
            'title_en' => 'required|string|min:2',
            'start_date' => 'required|string|date|min:2',
            'end_date' => 'required|string|date|min:2',
            'quantity' => 'required|string',
            'price' => 'required|string',
            'category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return GeneralApi::response()->json($validator->errors(), 422);
        }

        $product = Product::query()->findOrFail($id);
        $product->title = [
            'ar' => $request->title_ar,
            'en' => $request->title_en,
        ];
        $product->start_date = $request->start_date;
        $product->end_date = $request->end_date;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->vendor_id = Auth::guard('vendor_api')->user()->id;
        $product->save();

        if ($request->file('images')) {
            foreach ($request->images as $file) {
                $image = new Image();
                $image->product_id = $product->id;
                $image->path = Storage::disk('uploadFile')->put('products', $file);
                $image->save();
            }
        }

        return GeneralApi::returnData(201, 'Update Successfully', $product);

    }

    public function destroy($id)
    {
        $product = Product::query()->findOrFail($id);


        foreach ($product->images as $image) {
            Storage::disk('uploadFile')->delete($image->path);
        }

        $product->delete();

        return GeneralApi::returnData(200, 'Delete Successfully', $product);
    }
}
