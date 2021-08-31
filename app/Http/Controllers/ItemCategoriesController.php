<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\ItemCategory;
use App\Models\Item;

class ItemCategoriesController extends Controller
{
    
    public function store(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();

        if(ItemCategory::where('desc', '=', $request->input('desc'))->exists())
            return redirect()->back()->with('warning', 'Submission Failed. There is already an existing category named '. $request->input('desc'));

        $validator = Validator::make($request->all(), [
            'desc' => 'required|regex:/[A-Za-z0-9]+/|max:100',                    
        ]);    

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput(); 

        $category = new ItemCategory;

        $category->desc = $request->input('desc');
        
        $category->save();

        return redirect('admin/inventory')->with('success', 'Category '. ucfirst($category->desc ) . ' has been successfully added');    

    }

    public function update(Request $request){

        if($request->method() != 'POST')
        return redirect()->back();

        if(ItemCategory::where('desc', '=', $request->input('desc'))->where('id', '!=', $request->input('id'))->exists())
            return redirect()->back()->with('warning', 'Update Failed. There is already an existing category named '. $request->input('desc'));

        $validator = Validator::make($request->all(), [
            'desc' => 'required|regex:/[A-Za-z0-9]+/|max:100',                    
        ]);    

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();  

        $category = ItemCategory::find($request->input('id'));

        $oldname = $category->desc;
        $category->desc = $request->input('desc');

        $category->save();

        return redirect('admin/inventory')->with('info', 'Category '. ucfirst($oldname) . ' has been successfully Updated');    

    }

    public function delete(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();

        $category = ItemCategory::find($request->input('id'));

        if(Item::where('item_category_id', $category->id)->exists())
            return redirect()->back()->with('warning', 'Deletion Failed. There are still items registered to '. ucfirst($category->desc) . ' category');

        $oldname = ucfirst($category->desc);

        $category->delete();

        return redirect('admin/inventory')->with('info', 'Item ' . $oldname . ' has been successfully deleted.');

    }

}
