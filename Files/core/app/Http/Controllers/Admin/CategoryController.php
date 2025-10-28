<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Category";
        $categories = Category::searchable(['name'])->withCount('services')->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }

    public function categorySave(Request $request, $id = 0)
    {

        $isRequired = $id ? 'nullable' : 'required';
        $request->validate([
            'name'  => 'required|unique:categories,name,'.$id.',id',
            'image' => [$isRequired, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if (!$id) {
            $category = new Category();
            $notify[] = ['success', 'Category added successfully'];
        } else {
            $category = Category::findOrFail($id);
            $notify[] = ['success', 'Category updated successfully'];
        }

        if ($request->hasFile('image')) {
            try {
                $old             = $category->image;
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $slug           = slug($request->name);
        $category->name = $request->name;
        $category->slug = $slug;

        $category->save();

        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Category::changeStatus($id);
    }
}
