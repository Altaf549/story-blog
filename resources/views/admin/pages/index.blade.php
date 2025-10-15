@extends('admin.layouts.app')

@section('title', 'Settings Pages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
	<h2 class="mb-0">Settings</h2>
</div>

@php
$pageLabels = [
	'privacy_policy' => 'Privacy Policy',
	'terms_and_conditions' => 'Terms & Conditions',
	'about_us' => 'About Us',
	'contact_us' => 'Contact Us',
];
@endphp

<div class="row g-4">
	@foreach ($pageLabels as $key => $label)
		@php $page = $pages[$key] ?? null; @endphp
		<div class="col-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<strong>{{ $label }}</strong>
				</div>
				<div class="card-body">
					<form method="POST" action="{{ route('admin.pages.store') }}">
						@csrf
						<input type="hidden" name="key" value="{{ $key }}">
						<div class="mb-3">
							<label class="form-label">Title</label>
							<input type="text" name="title" class="form-control" value="{{ old('title', optional($page)->title) }}" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Content</label>
							<textarea name="content" class="form-control" rows="6">{{ old('content', optional($page)->content) }}</textarea>
						</div>
						<button type="submit" class="btn btn-primary">Save</button>
					</form>
				</div>
			</div>
		</div>
	@endforeach
	@if (session('success'))
		<div class="col-12">
			<div class="alert alert-success">{{ session('success') }}</div>
		</div>
	@endif
</div>
@endsection



