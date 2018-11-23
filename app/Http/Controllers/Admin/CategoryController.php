<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

//use Illuminate\Support\Facades\Storage;
//use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories',
            'image' => 'required|mimes:jpeg, bmp, png, jpg'
        ]);

        $image = $request->file('image');
        $slug = str_slug($request->name);

        if(isset($image))
        {
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            $categoryimagepath = 'storage/category/' . $imagename;
            $sliderpath = 'storage/category/slider/' . $imagename;

            Image::make($image)->resize(1600,479)->save($categoryimagepath);
            Image::make($image)->resize(500,333)->save($sliderpath);

        }else{
            $imagename = "default.png";
        }

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imagename;
        $category->save();

        Toastr::success('Category Successfully Saved :)', 'Success');
        return redirect()->route('admin.category.index');
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
        $category = Category::find($id);
        return view('admin.category.edit', compact('category'));
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
        $this->validate($request, [
            'name' => 'required|unique:categories',
            'image' => 'required|mimes:jpeg, bmp, png, jpg'
        ]);

        $category = Category::find($id);
        $category->name = $request->name;
        $slug = str_slug($request->name);
        $category->slug = $slug;

        \Storage::delete('category/'.$category->image);
        \Storage::delete('category/slider/'.$category->image);

        $image = $request->file('image');
        if(isset($image))
        {
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            $categoryimagepath = 'storage/category/' . $imagename;
            $sliderpath = 'storage/category/slider/' . $imagename;

            Image::make($image)->resize(1600,479)->save($categoryimagepath);
            Image::make($image)->resize(500,333)->save($sliderpath);

        }else{
            $imagename = "default.png";
        }
        $category->image = $imagename;

        $category->save();
        Toastr::success('Category Successfully Updated :)', 'Success');

        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        \Storage::delete('category/'.$category->image);
        \Storage::delete('category/slider/'.$category->image);

        $category->delete();

        Toastr::success('Category Successfully Deleted ")', 'Success');
        return redirect()->back();
    }
}
