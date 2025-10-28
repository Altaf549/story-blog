@extends('layouts.app')

@section('title', $story->title.' - Story Blog')

@section('content')
    <div class="mb-6">
        <a href="{{ route('stories.index.public') }}" class="text-blue-600">← Back to Stories</a>
    </div>

    <article class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-3">{{ $story->title }}</h1>
        <div class="text-sm text-gray-600 mb-4">{{ optional($story->category)->name }}</div>

        @if($story->image_id)
            <img src="https://drive.google.com/thumbnail?id={{ $story->image_id }}&sz=w1200" alt="{{ $story->title }}" style="width:100%;height:auto;" class="mb-6">
        @endif

        <div class="prose max-w-none">
            {!! $story->content !!}
        </div>
    </article>
@endsection


