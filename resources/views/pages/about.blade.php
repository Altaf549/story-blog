@extends('layouts.app')

@section('title', ($page->title ?? 'About Us') . ' - Story Blog')

@section('content')
    <div>
        <h1 class="text-2xl font-semibold mb-4">{{ $page->title ?? 'About Us' }}</h1>
        <div class="prose max-w-none">{!! $page->content ?? 'No about us content available.' !!}</div>
    </div>
@endsection


