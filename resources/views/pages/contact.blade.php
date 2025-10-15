@extends('layouts.app')

@section('title', ($page->title ?? 'Contact Us') . ' - Story Blog')

@section('content')
    <div class="max-w-4xl mx-auto bg-white border rounded-md shadow-sm p-6">
        <h1 class="text-2xl font-semibold mb-4">{{ $page->title ?? 'Contact Us' }}</h1>
        @if($page)
            <div class="space-y-2">
                @if($page->phone_no)
                    <div><span class="font-medium">Phone:</span> {{ $page->phone_no }}</div>
                @endif
                @if($page->email)
                    <div><span class="font-medium">Email:</span> {{ $page->email }}</div>
                @endif
                @if($page->address)
                    <div><span class="font-medium">Address:</span> {{ $page->address }}</div>
                @endif
            </div>
        @else
            <p>No contact information available.</p>
        @endif
    </div>
@endsection


