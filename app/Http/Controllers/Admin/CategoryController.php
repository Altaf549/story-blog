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
            $query = Category::select(['id', 'name', 'slug', 'description', 'image_id', 'is_active', 'created_at']);
            
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('image_id', function ($row) {
                    if (!$row->image_id) {
                        return '<span class="text-muted">—</span>';
                    }
                    return '<img src="https://drive.google.com/thumbnail?id=' . $row->image_id . '&sz=w200" alt="Category" style="max-width: 100px; max-height: 50px; object-fit: contain;">';
                })
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
                ->rawColumns(['image_id', 'action', 'is_active'])
                ->make(true);
        }

        return view('admin.categories.index');
    }

    /**
     * Store a newly created or updated resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'image_id' => [$request->id ? 'nullable' : 'required', 'string', 'max:255'],
        ]);

        $category = $request->id ? Category::findOrFail($request->id) : new Category();
        
        $category->name = $validated['name'];
        $category->description = $validated['description'] ?? null;
        $category->is_active = $request->boolean('is_active', true);
        $category->image_id = $validated['image_id'] ?? null;
        
        $category->save();

        return response()->json(['message' => 'Category saved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $data = $category->toArray();
        
        // Return the image_id
        $data['image_id'] = $category->image_id;
        
        return response()->json($data);
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
            'image_id' => 'required|string|max:255',
        ]);

        $category->name = $validated['name'];
        $category->description = $validated['description'] ?? null;
        $category->is_active = $validated['is_active'];
        $category->image_id = $validated['image_id'];
        
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
