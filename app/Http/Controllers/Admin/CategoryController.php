<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();
        return view('admin.category.index', compact('categories'));
    }

    public function store(Request $request) {
//
        if ($request->file('image')){
            try {
                $request->validate([
                    'title_en' => 'required|string|min:2',
                    'title_ar' => 'required|string|min:2',
                    'image'=> 'required'
                ]);

                $category = new Category();
                $category->title = [
                    'en' => $request->title_en,
                    'ar' => $request->title_ar,
                ];
                $category->image = Storage::disk('uploadFile')->put('categories', $request->image);

                $category->save();

                return back()->with('success', 'Created Successfully');
            }catch (\Exception $exception) {
                return back()->withErrors(['error' => $exception->getMessage()]);
            }
        }


    }

    public function update(Request $request) {
        $request->validate([
            'title_en' => 'required|string|min:2',
            'title_ar' => 'required|string|min:2',

        ]);

        if ($request->file('image')){
            try {

             $cat = Category::where('id',$request->id)->update([

                 'title'=>[
                     'ar'=>$request->title_ar,
                     'en'=>$request->title_en
                 ],
                  'image'=> Storage::disk('uploadFile')->put('categories', $request->image)
              ]);

                return back()->with('success', 'Update Successfully');
            }catch (\Exception $exception) {
                return back()->withErrors(['error' => $exception->getMessage()]);
            }
        }else{
            Category::where('id',$request->id)->update([
                'title'=>[
                    'ar'=>$request->title_ar,
                    'en'=>$request->title_en
                ],
            ]);
            return back()->with('success', 'Update Successfully');
        }
    }

    public function destroy($id) {
        try {
            $cat = Category::where('id',$id)->get();
            Storage::disk('uploadFile')->delete($cat[0]->image);
            Category::destroy($id);
            return back()->with('success', 'Deleted Successfully');

        }catch (\Exception $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
    }
}
