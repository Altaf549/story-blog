@extends('layouts.app')

@section('title', ($page->title ?? 'Privacy Policy') . ' - Story Blog')

@section('content')
    <div>
        <h1 class="text-2xl font-semibold mb-4">{{ $page->title ?? 'Privacy Policy' }}</h1>
        <div class="prose max-w-none">{!! $page->content ?? 'No privacy policy available.' !!}</div>
    </div>
@endsection


