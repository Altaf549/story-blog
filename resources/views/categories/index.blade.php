@extends('layouts.app')

@section('title', 'All Categories - Story Blog')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">All Categories</h1>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @forelse($categories as $category)
            <a href="{{ route('categories.stories.public', $category->slug) }}" class="block border rounded p-3 text-center">
                @if($category->image_id)
                    <img src="https://drive.google.com/thumbnail?id={{ $category->image_id }}&sz=w1200" alt="{{ $category->name }}" style="width:100%;height:120px;object-fit:cover;">
                @endif
                <div class="mt-2 font-medium">{{ $category->name }}</div>
            </a>
        @empty
            <p>No categories available.</p>
        @endforelse
    </div>
@endsection


