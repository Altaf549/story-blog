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
            $query = Category::select(['id', 'name', 'slug', 'description', 'is_active', 'created_at']);
            
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
        ]);

        $category = $request->id ? Category::findOrFail($request->id) : new Category();
        
        $category->name = $request->name;
        $category->description = $request->description;
        $category->is_active = $request->is_active ?? true;
        $category->save();

        return response()->json(['success' => 'Category saved successfully.']);
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        
        return response()->json(['success' => 'Category deleted successfully.']);
    }
}
