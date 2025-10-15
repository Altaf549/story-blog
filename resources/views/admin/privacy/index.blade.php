@extends('admin.layouts.app')

@section('title', 'Privacy Policy')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">
@endpush

@section('content')
<h2 class="mb-4">Privacy Policy</h2>
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="POST" action="{{ route('admin.privacy.store') }}">
	@csrf
	<div class="mb-3">
		<label class="form-label">Title</label>
		<input type="text" name="title" class="form-control" value="{{ old('title', optional($record)->title) }}" required>
	</div>
	<div class="mb-3">
		<label class="form-label">Content</label>
		<textarea name="content" id="contentEditor" class="form-control" rows="10">{{ old('content', optional($record)->content) }}</textarea>
	</div>
	<button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function loadScript(src, onload, onerror) {
        var s = document.createElement('script');
        s.src = src;
        s.onload = onload;
        if (onerror) s.onerror = onerror;
        document.body.appendChild(s);
    }

    function loadStyle(href) {
        var l = document.createElement('link');
        l.rel = 'stylesheet';
        l.href = href;
        document.head.appendChild(l);
    }

    // Ensure Summernote CSS exists (fallback like Stories)
    setTimeout(function() {
        if (!document.querySelector('link[href*="summernote"], link[href*="Summernote"]')) {
            loadStyle('https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs5.min.css');
        }
    }, 0);

    let summernoteInitialized = false;
    function initSummernote() {
        if (summernoteInitialized) return;
        if (window.jQuery && jQuery.fn.summernote) {
            jQuery('#contentEditor').summernote({
                placeholder: 'Write content...',
                height: 250
            });
            summernoteInitialized = true;
        }
    }

    if (window.jQuery && jQuery.fn.summernote) {
        initSummernote();
    } else {
        // Fallback loader chain (like Stories)
        loadScript(
            'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs5.min.js',
            function() { if (window.jQuery && jQuery.fn.summernote) { initSummernote(); } },
            function() {
                loadScript(
                    'https://unpkg.com/summernote@0.8.18/dist/summernote-bs5.min.js',
                    function() { if (window.jQuery && jQuery.fn.summernote) { initSummernote(); } },
                    function() {
                        loadStyle('https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css');
                        loadScript(
                            'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js',
                            function() { if (window.jQuery && jQuery.fn.summernote) { initSummernote(); } }
                        );
                    }
                );
            }
        );
    }

    // Ensure Summernote HTML is submitted with the form (like Stories)
    var form = document.querySelector('form[action="'+@json(route('admin.privacy.store'))+'"]');
    if (form) {
        form.addEventListener('submit', function() {
            if (window.jQuery && jQuery.fn.summernote) {
                var htmlContent = jQuery('#contentEditor').summernote('code');
                var textarea = document.getElementById('contentEditor');
                if (textarea) textarea.value = htmlContent;
            }
        });
    }
});
</script>
@endpush


