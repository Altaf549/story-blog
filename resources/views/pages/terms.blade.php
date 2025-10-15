@extends('layouts.app')

@section('title', ($page->title ?? 'Terms & Conditions') . ' - Story Blog')

@section('content')
    <div>
        <h1 class="text-2xl font-semibold mb-4">{{ $page->title ?? 'Terms & Conditions' }}</h1>
        <div class="prose max-w-none">{!! $page->content ?? 'No terms and conditions available.' !!}</div>
    </div>
@endsection


