<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Category::select(['id', 'name', 'slug', 'description', 'image', 'is_active', 'created_at']);
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    return '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm me-1" data-id="'.$row->id.'">Edit</a>' .
                           '<a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                })
                ->editColumn('is_active', function($row) {
                    return $row->is_active 
                        ? '<span class="badge bg-success">Active</span>' 
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }

        return view('admin.categories.index');
    }

    /**
     * Store a newly created or updated resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $category = $request->id ? Category::findOrFail($request->id) : new Category();
        
        $category->name = $request->name;
        $category->description = $request->description;
        $category->is_active = $request->is_active ?? true;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $category->image = $path;
        }
        $category->save();

        return response()->json(['message' => 'Category saved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $category->name = $validated['name'];
        $category->description = $validated['description'] ?? null;
        $category->is_active = $validated['is_active'];
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $category->image = $path;
        }
        $category->save();

        return response()->json(['message' => 'Category updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        
        return response()->json(['success' => 'Category deleted successfully.']);
    }
}
