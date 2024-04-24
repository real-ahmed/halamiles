<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $pageTitle = 'All Categories';
        $categories = Category::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }

    public function save(Request $request, $id=0){
        $imgValidation = $id ? 'nullable' : 'required';
        $request->validate([
            'name' => 'required|max:40',
            'icon' => 'required',
            'image'       => ["$imgValidation", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],


        ]);

        $category = new Category();
        $notification = 'added';
        $oldImage     = '';
        if($id){
            $category = Category::findOrFail($id);
            $notification = 'updated';
            $category->status = $request->status ? 1 : 0;
            $oldImage     = $category->image;
        }
        if ($request->hasFile('image')) {
            try {
                $category->image = fileUploader($request->image, getFilePath('category'), null, $oldImage);
            } catch (\Exception$e) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $category->name = $request->name;
        $category->icon = $request->icon;
        $category->save();

        $notify[] = ['success', "Category $notification successfully"];
        return back()->withNotify($notify);
    }
}
