@extends('layouts.app')

@section('title', 'Home - Story Blog')

@section('content')
        @if($banner)
            <section class="mb-8">
                <a href="{{ $banner->link_url ?? '#' }}" class="block">
                    <img src="{{ asset('storage/'.$banner->image_path) }}" alt="{{ $banner->title }}" style="width:100%;height:auto;">
                </a>
            </section>
        @endif

        <section class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Categories</h2>
                <a href="{{ route('categories.index.public') }}">See All</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @forelse($categories as $category)
                    <a href="{{ route('categories.stories.public', $category->slug) }}" class="block border rounded p-3 text-center">
                        @if($category->image)
                            <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}" style="width:100%;height:120px;object-fit:cover;">
                        @endif
                        <div class="mt-2 font-medium">{{ $category->name }}</div>
                    </a>
                @empty
                    <p>No categories available.</p>
                @endforelse
            </div>
        </section>

        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Recent Stories</h2>
                <a href="{{ route('stories.index.public') }}">See All</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($recentStories as $story)
                    <article class="border rounded overflow-hidden">
                        @if($story->banner_image)
                            <img src="{{ asset('storage/'.$story->banner_image) }}" alt="{{ $story->title }}" style="width:100%;height:180px;object-fit:cover;">
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-1">{{ $story->title }}</h3>
                            <div class="text-sm text-gray-600">{{ optional($story->category)->name }}</div>
                            <p class="mt-2 text-sm" style="display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ Str::limit(strip_tags($story->content), 400) }}
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('stories.show.public', $story) }}" class="inline-block px-3 py-1 text-sm bg-blue-600 text-white rounded">Read more</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p>No stories yet.</p>
                @endforelse
            </div>
        </section>
@endsection


