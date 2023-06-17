<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    public function my_card ($id)
    {
      $card = Card::where('user_id', $id)->get();
//      return $card;
        if (! $card) {
            return response()->json([
                "status" => 204,
                "msg" => "No data",
                "data" => ''
            ]);
        }
        $count =0;
        $product =array();
        foreach ($card  as $item) {
            $count++;
            $pro = Product::where('id', $item->product_id)->get();
            array_push($product,$pro[0]);
        }



        if (! $product) {
            return response()->json([
                "status" => 204,
                "msg" => "No data",
                "data" => ''
            ]);
        }


        return response()->json([
            "status" => 200,
            "msg" => "Successfully",
            "data" => $product
        ]);
    }

    public function add_to_card(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Card::create([
            'user_id'=>$request->user_id,
            'product_id'=>$request->product_id
        ]);
        return response()->json([
            "status" => 200,
            "msg" => "Successfully",
            "data" => []
        ]);


    }
    public function delete_product_in_my_card ($id){
        Card::destroy($id);
        return response()->json([
            "status" => 200,
            "msg" => "Deleted Successfully",
            "data" => []
        ]);

    }
}
