<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class AdminVendorController extends Controller
{
    public function show_vendor()
    {
        $vendors = Vendor::all();
        return view('admin/vendor/index', compact('vendors'));
    }

    public function show_product_vendor($id)
    {
        $vendor_product = Product::where('vendor_id', $id)->get();
        return view('admin/vendor/product', compact('vendor_product'));
    }


    public function sharing_product_vendor($id)
    {
        Product::where('id', $id)->update([
            'status' => 1
        ]);
        return back()->with('success', 'Sharing Successfully');
    }

    public function unsharing_product_vendor($id)
    {
        Product::where('id', $id)->update([
            'status' => 0
        ]);
        return back()->with('success', 'UN Sharing Successfully');
    }

    public function add_percentage_product(Request $request)
    {
        Product::where('id', $request->product_id)->update([
            'percentage' => $request->percentage
        ]);
        return back()->with('success', 'Add Percentage Successfully');
    }

    public function all_product_unsharing()
    {
        $products = Product::where('status', 0)->get();
        return view('admin/pages/all_product', compact('products'));
    }

    public function get_vendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('admin/vendor/show', compact('vendor'));
    }

    public function save_vendor(Request $request)
    {

        try {

            $request->validate([
                'name' => 'required|string|min:2',
                'phone' => 'required|string|min:11',
                'email' => 'required|email|unique:vendors,email',
                'password' => 'required|string|confirmed',
            ]);

            Vendor::create([
               'name'=>$request->name,
                'phone'=>$request->phone,
                'email'=>$request->email,
                'password'=>bcrypt($request->password)

            ]);
            return back()->with('success', 'Created Successfully');


        }catch (\Exception $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function update_vendor(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|min:2',
                'email' => 'required|email|unique:vendors,email,' . $request->vendor_id,
                'password' => 'confirmed',
            ]);
            if ($request->password) {
                Vendor::where('id', $request->vendor_id)->update([
                    'name' => $request->name,
                    'password' => bcrypt($request->password),
                    'email' => $request->email,
                    'phone' => $request->Phone

                ]);

            } else {
                Vendor::where('id', $request->vendor_id)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->Phone
                ]);
            }

            return back()->with('success', 'Updated Successfully');

        } catch (\Exception $exception) {
            return back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    public function delete_vendor($id)
    {
        Vendor::destroy($id);
        return back()->with('success', 'Deleted Successfully');
    }
}
