<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Home;
use App\Models\Product;
use App\Models\Slider;
use App\Traits\GeneralApi;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function category()
    {
        $category = Category::all();

        if ($category->count() == 0) {
            return response()->json([
                "status" => 204,
                "msg" => "No Content",
                "data" => []
            ]);
        }
        if (!$category) {
            return response()->json([
                "status" => 404,
                "msg" => "Not Found",
                "data" => []
            ]);
        }
        return response()->json([
            "status" => 200,
            "msg" => "Successfully",
            "data" => $category
        ]);
    }

    public function findCategory($id)
    {
        $category = Category::query()->findOrFail($id);

        if ($category->count() == 0) {
            return GeneralApi::returnData(204, 'No Content', []);
        }
        if (!$category) {
            return GeneralApi::returnData(404, 'No Found', []);
        }
        return GeneralApi::returnData(200, 'Successfully', $category);
    }

    public function products()
    {
        $products = Product::query()
            ->where('status', 1)
            ->select('id', 'title', 'start_date', 'end_date', 'quantity', 'total_price')
            ->with('images')
            ->get();

        if ($products->count() == 0) {
            return GeneralApi::returnData(204, 'No Content', []);
        }
        if (!$products) {
            return GeneralApi::returnData(404, 'No Found', []);
        }
        return GeneralApi::returnData(200, 'Successfully', $products);
    }

    public function productWithCategory()
    {
        $products = Product::with('category', 'images')->get();


        if ($products->count() == 0) {
            return GeneralApi::returnData(204, 'No Content', []);
        }
        if (!$products) {
            return GeneralApi::returnData(404, 'No Found', []);
        }
        return GeneralApi::returnData(200, 'Successfully', $products);
    }

    public function productWithCategoryId($id)
    {
       $product = Product::query()->where('category_id', $id)->with('images')->get();
        return GeneralApi::returnData(201, 'Success', $product);
    }

    public function All_Slider()
    {
        $slider = Slider::all();

        if ($slider->count() == 0) {
            return response()->json([
                "status" => 204,
                "msg" => "No Content",
                "data" => []
            ]);
        }
        return response()->json([
            "status" => 200,
            "msg" => "Successfully",
            "data" => $slider
        ]);
    }

    public function about()
    {

        $about = Home::all();

        return response()->json([
            "status" => 200,
            "msg" => "Successfully",
            "data" => $about[0]
        ]);
    }
}
