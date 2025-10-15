@extends('layouts.app')

@section('title', 'All Stories - Story Blog')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">All Stories</h1>
        <a href="{{ route('home') }}" class="text-blue-600">Home</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($stories as $story)
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
                </div>
            </article>
        @empty
            <p>No stories available.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $stories->links() }}
    </div>
@endsection


