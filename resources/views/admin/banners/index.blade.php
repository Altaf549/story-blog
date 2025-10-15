@extends('admin.layouts.app')

@section('title', 'Banners')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
	<h2 class="mb-0">Banners</h2>
	<button class="btn btn-primary" id="createBanner">Add Banner</button>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered" id="banners-table">
	<thead>
		<tr>
			<th>#</th>
			<th>Title</th>
			<th>Image</th>
			<th>Link</th>
			<th>Active</th>
			<th>Position</th>
			<th>Created</th>
			<th>Action</th>
		</tr>
	</thead>
</table>

<!-- Modal -->
<div class="modal fade" id="bannerModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Banner</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
            <div class="modal-body">
                <form id="bannerForm" method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="id" id="banner_id">
					<div class="mb-3">
						<label class="form-label">Title</label>
						<input type="text" class="form-control" name="title" id="title" required>
					</div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control" name="image" id="image" accept="image/*">
                        <small class="text-muted">Upload a banner image. Required for new banners.</small>
                    </div>
					<div class="mb-3">
						<label class="form-label">Link URL</label>
						<input type="url" class="form-control" name="link_url" id="link_url">
					</div>
					<div class="form-check mb-3">
						<input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
						<label class="form-check-label" for="is_active">Active</label>
					</div>
					<div class="mb-3">
						<label class="form-label">Position</label>
						<input type="number" min="0" class="form-control" name="position" id="position" value="0">
					</div>
					<button type="submit" class="btn btn-primary">Save</button>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
	const table = $('#banners-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: '{{ route('admin.banners.index') }}',
        columns: [
			{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
			{ data: 'title', name: 'title' },
            { data: 'image_path', name: 'image_path' },
			{ data: 'link_url', name: 'link_url', render: function(data){ return data ? '<a href="'+data+'" target="_blank">'+data+'</a>' : ''; } },
			{ data: 'is_active', name: 'is_active', orderable: false, searchable: false },
			{ data: 'position', name: 'position' },
			{ data: 'created_at', name: 'created_at' },
			{ data: 'action', name: 'action', orderable: false, searchable: false },
		]
	});

	$('#createBanner').on('click', function(){
		$('#banner_id').val('');
		$('#bannerForm')[0].reset();
		$('#bannerModal').modal('show');
	});

	$('#banners-table').on('click', '.edit', function(){
		const id = $(this).data('id');
        $.get('/admin/banners/'+id+'/edit', function(data){
			$('#banner_id').val(data.id);
			$('#title').val(data.title);
			$('#link_url').val(data.link_url || '');
			$('#is_active').prop('checked', !!data.is_active);
			$('#position').val(data.position || 0);
			$('#bannerModal').modal('show');
		});
	});

	$('#banners-table').on('click', '.delete', function(){
		if (!confirm('Delete this banner?')) return;
		const id = $(this).data('id');
		$.ajax({
			url: '/admin/banners/'+id,
			type: 'POST',
			data: { _method: 'DELETE', _token: $('meta[name="csrf-token"]').attr('content') },
			success: function(){ table.ajax.reload(null, false); }
		});
	});
});
</script>
@endpush


