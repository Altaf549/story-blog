@extends('admin.layouts.app')

@section('title', 'Contact Us')

@push('styles')
@endpush

@section('content')
<h2 class="mb-4">Contact Us</h2>
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="POST" action="{{ route('admin.contact.store') }}">
	@csrf
	<div class="mb-3">
		<label class="form-label">Title</label>
		<input type="text" name="title" class="form-control" value="{{ old('title', optional($record)->title) }}" required>
	</div>
    <div class="mb-3">
        <label class="form-label">Phone No</label>
        <input type="text" name="phone_no" class="form-control" value="{{ old('phone_no', optional($record)->phone_no) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', optional($record)->email) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="4">{{ old('address', optional($record)->address) }}</textarea>
    </div>
	<button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection

@push('scripts')
@endpush


