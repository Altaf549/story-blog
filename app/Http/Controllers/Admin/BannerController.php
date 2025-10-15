<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
	public function index(Request $request): View|\Illuminate\Http\JsonResponse
	{
		if ($request->ajax()) {
			$query = Banner::select(['id','title','image_path','link_url','is_active','position','created_at']);
            return DataTables::of($query)
				->addIndexColumn()
                ->editColumn('image_path', function ($row) {
                    if (!$row->image_path) {
                        return '';
                    }
                    $url = Storage::disk('public')->url($row->image_path);
                    return '<img src="'.$url.'" alt="'.$row->title.'" style="height:40px" />';
                })
				->editColumn('is_active', function ($row) {
					return $row->is_active
						? '<span class="badge bg-success">Active</span>'
						: '<span class="badge bg-danger">Inactive</span>';
				})
                ->addColumn('action', function ($row) {
					return '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm me-1" data-id="'.$row->id.'">Edit</a>' .
					       '<a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
				})
                ->rawColumns(['image_path','is_active','action'])
				->make(true);
		}

		return view('admin.banners.index');
	}

	public function store(Request $request): RedirectResponse
	{
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'image' => [$request->id ? 'nullable' : 'required','image','mimes:jpeg,png,jpg,gif,webp','max:5120'],
            'link_url' => ['nullable','url','max:255'],
            'is_active' => ['nullable','boolean'],
            'position' => ['nullable','integer','min:0'],
        ]);

        $banner = $request->id ? Banner::findOrFail($request->id) : new Banner();
        $banner->title = $validated['title'];
        $banner->link_url = $validated['link_url'] ?? null;
        $banner->is_active = $request->boolean('is_active', true);
        $banner->position = $validated['position'] ?? 0;

        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($banner->exists && $banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $path = $request->file('image')->store('banners', 'public');
            $banner->image_path = $path;
        }

        $banner->save();

		return back()->with('success', 'Banner saved');
	}

	public function edit($id)
	{
		return response()->json(Banner::findOrFail($id));
	}

	public function update(Request $request, Banner $banner): RedirectResponse
	{
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'image' => ['nullable','image','mimes:jpeg,png,jpg,gif,webp','max:5120'],
            'link_url' => ['nullable','url','max:255'],
            'is_active' => ['required','boolean'],
            'position' => ['required','integer','min:0'],
        ]);

        $banner->title = $validated['title'];
        $banner->link_url = $validated['link_url'] ?? null;
        $banner->is_active = $validated['is_active'];
        $banner->position = $validated['position'];

        if ($request->hasFile('image')) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $path = $request->file('image')->store('banners', 'public');
            $banner->image_path = $path;
        }

        $banner->save();
        return back()->with('success', 'Banner updated');
	}

	public function destroy(Banner $banner): RedirectResponse
	{
		$banner->delete();
		return back()->with('success', 'Banner deleted');
	}
}


