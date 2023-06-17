<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $slider = Slider::all();

        return view('admin/slider/index', compact('slider'));

    }

    public function create(Request $request)
    {
        $request->validate([
            'title_en' => 'required',
            'title_ar' => 'required',
            'description_en' => 'required',
            'description_ar' => 'required',
            'image' => 'required',

        ]);
        if ($request->file('image')) {

            Slider::create([
                'title' => [
                    'en' => $request->title_en,
                    'ar' => $request->title_ar,
                ],
                'description' => [
                    'en' => $request->description_en,
                    'ar' => $request->description_ar,
                ],

                'image' => Storage::disk('uploadFile')->put('slider', $request->image)
            ]);
            return back()->with('success', 'Created Successfully');

        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'title_en' => 'required',
            'title_ar' => 'required',
            'description_en' => 'required',
            'description_ar' => 'required',

        ]);
//        return $request;
        if ($request->file('image')) {
           $slider =  Slider::findOrFail($request->id);
            Storage::disk('uploadFile')->delete($slider->image);

            Slider::where('id', $request->id)->update([


                'title' => [
                    'en' => $request->title_en,
                    'ar' => $request->title_ar,
                ],
                'description' => [
                    'en' => $request->description_en,
                    'ar' => $request->description_ar,
                ],

                'image' => Storage::disk('uploadFile')->put('slider', $request->image)

            ]);
            return back()->with('success', 'Created Successfully');
        } else {
            Slider::where('id', $request->id)->update([

                'title' => [
                    'en' => $request->title_en,
                    'ar' => $request->title_ar,
                ],
                'description' => [
                    'en' => $request->description_en,
                    'ar' => $request->description_ar,
                ],


            ]);

        }


        return back()->with('success', 'Update Successfully');

    }

    public function delete($id)
    {


        $slider = Slider::where('id', $id)->get();
        Storage::disk('uploadFile')->delete($slider[0]->image);

        Slider::destroy($id);
        return back()->with('success', 'Deleted Successfully');


    }


}
