@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Categories</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                        Add New Category
                    </button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered" id="categories-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->

<!-- Add/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="categoryId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Google Drive Image ID</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="image_id" id="image_id" placeholder="Enter Google Drive Image ID" required>
                            <button type="button" class="btn btn-outline-secondary" id="previewImage">Preview</button>
                        </div>
                        <small class="text-muted">Enter the Google Drive Image ID (e.g., 1a2b3c4d5e6f7g8h9i0j)</small>
                        <div class="mt-2" id="imagePreviewContainer" style="display:none;">
                            <img id="imagePreview" src="#" alt="Preview" style="height:80px; border:1px solid #ddd; object-fit:cover;" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this category? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    var table = $('#categories-table').DataTable({
        processing: true,
        serverSide: true,
        rowId: 'id',
        ajax: {
            url: "{{ route('admin.categories.index') }}",
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTables error:', xhr.responseText);
                toastr.error('An error occurred while loading the table data.');
                console.log('Error details:', { error: error, thrown: thrown });
            },
            dataSrc: function(json) {
                console.log('DataTables response:', json); // Debug log
                return json.data || [];
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'image_id', name: 'image_id', orderable: false, searchable: false, 
             render: function(data, type, row) {
                if (!data || data === '') {
                    return '<span class="text-muted">—</span>';
                }
                // Check if the data is already an image tag (from server-side rendering)
                if (type === 'display' && data.startsWith('<img')) {
                    return data;
                }
                // If it's just the image ID, create the image tag
                return '<img src="https://drive.google.com/thumbnail?id=' + data + '&sz=w200" alt="Category Image" style="width:42px;height:42px;object-fit:cover;border-radius:4px;">';
            }},
            {data: 'slug', name: 'slug'},
            {data: 'description', name: 'description', render: function(data) {
                return data || 'N/A';
            }},
            {data: 'is_active', name: 'is_active', render: function(data) {
                return data ? '<span class="badge bg-success">Active</span>' : 
                             '<span class="badge bg-danger">Inactive</span>';
            }},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        responsive: true,
        autoWidth: false
    });

    // Reset form specifically when clicking the Add button to avoid overriding edit state
    $(document).on('click', '[data-bs-target="#categoryModal"][data-bs-toggle="modal"]', function () {
        $('#categoryForm')[0].reset();
        $('#categoryId').val('');
        $('#modalTitle').text('Add New Category');
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');
        $('#imagePreview').attr('src', '#');
        $('#imagePreviewContainer').hide();
    });

    // Handle edit button click
    $('body').on('click', '.edit', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        // Use the correct route with the category ID
        var editUrl = '{{ route("admin.categories.edit", ["category" => "__ID__"]) }}';
        editUrl = editUrl.replace('__ID__', id);
        
        $.ajax({
            url: editUrl,
            type: 'GET',
            success: function(response) {
                console.log('Edit response:', response); // Debug log
                
                // Reset form and set values
                $('#categoryForm')[0].reset();
                $('#categoryId').val(response.id);
                $('#name').val(response.name);
                $('#description').val(response.description || '');
                $('#is_active').prop('checked', response.is_active);
                
                // Set image preview if image_id exists
                if (response.image_id) {
                    console.log('Setting image_id:', response.image_id); // Debug log
                    $('#image_id').val(response.image_id);
                    $('#imagePreview').attr('src', 'https://drive.google.com/thumbnail?id=' + response.image_id + '&sz=w1000');
                    $('#imagePreviewContainer').show();
                } else {
                    console.log('No image_id found'); // Debug log
                    $('#image_id').val('');
                    $('#imagePreviewContainer').hide();
                }
                
                // Update modal title and show
                $('#modalTitle').text('Edit Category');
                $('#categoryModal').modal('show');
            },
            error: function(xhr) {
                console.error('Error fetching category:', xhr);
                toastr.error('Error loading category data');
            }
        });
    });

    // Reset form when modal is hidden
    $('#categoryModal').on('hidden.bs.modal', function () {
        var form = $('#categoryForm');
        form[0].reset();
        $('#imagePreviewContainer').hide();
        $('#categoryId').val('');
        $('#modalTitle').text('Add New Category');
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');
    });

    // Submit form
    $('#categoryForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);

        // Ensure is_active is always present in the form data
        if (!$('#is_active').is(':checked')) {
            // If unchecked, add a hidden field with value 0 (avoid duplicates)
            if ($('#categoryForm .is_active_hidden').length === 0) {
                $('#categoryForm').append('<input type="hidden" name="is_active" value="0" class="is_active_hidden">');
            }
        } else {
            // Remove hidden field if checked
            $('#categoryForm .is_active_hidden').remove();
        }

        var url = form.attr('action') || "{{ route('admin.categories.store') }}";
        var method = form.find('input[name="_method"]').val() || 'POST';

        // Debug log for troubleshooting
        console.log('Form submit:', { url: url, method: method });

        // Clear previous errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();

        var formData = new FormData(this);

        // Remove duplicate hidden is_active if present twice
        var hiddenFields = form.find('.is_active_hidden');
        if (hiddenFields.length > 1) {
            hiddenFields.slice(1).remove();
        }

        $.ajax({
            url: url,
            type: 'POST', // Always POST, Laravel will use _method for PUT
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#categoryModal').modal('hide');
                form[0].reset();
                table.ajax.reload();
                toastr.success((response && (response.message || response.success)) || 'Category saved successfully');
            },
            error: function(xhr) {
                // Debug log for backend error
                console.error('AJAX error:', xhr.status, xhr.responseText);
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        var input = form.find('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                    });
                } else {
                    toastr.error('An error occurred while saving the category');
                }
            }
        });
    });

    // Image preview for category
    $('#previewImage').on('click', function() {
        const imageId = $('#image_id').val().trim();
        console.log('Preview clicked, imageId:', imageId); // Debug log
        
        if (imageId) {
            const previewUrl = `https://drive.google.com/thumbnail?id=${imageId}&sz=w1000`;
            console.log('Setting preview URL:', previewUrl); // Debug log
            
            $('#imagePreview')
                .on('load', function() {
                    console.log('Image loaded successfully');
                    $('#imagePreviewContainer').show();
                })
                .on('error', function() {
                    console.error('Failed to load image');
                    toastr.error('Failed to load image. Please check the Image ID.');
                    $('#imagePreviewContainer').hide();
                })
                .attr('src', previewUrl);
        } else {
            console.log('No image ID provided'); // Debug log
            toastr.warning('Please enter a Google Drive Image ID');
            $('#imagePreviewContainer').hide();
        }
    });

    // Delete category
    var deleteId;
    $('body').on('click', '.delete', function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        if (!deleteId) return;
        
        var deleteButton = $(this);
        deleteButton.prop('disabled', true);
        
        // Use the correct route with the category ID
        var deleteUrl = '{{ route("admin.categories.destroy", ["category" => "__ID__"]) }}';
        deleteUrl = deleteUrl.replace('__ID__', deleteId);
        
        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                toastr.success('Category deleted successfully');
                table.draw();  // Refresh the table
            },
            error: function(xhr) {
                console.error('Delete error:', xhr);
                toastr.error('Error deleting category');
            },
            complete: function() {
                deleteButton.prop('disabled', false);
                deleteId = null;
            }
        });
    });
});
</script>
@endpush