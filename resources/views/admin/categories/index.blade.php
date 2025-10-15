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
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">Recommended square or 4:3, max 2MB.</div>
                        <div class="mt-2" id="catImagePreviewContainer" style="display:none;">
                            <img id="catImagePreview" src="#" alt="Preview" style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #ddd;" />
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
                console.error('DataTables error:', error);
                toastr.error('An error occurred while loading the table data.');
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'image', name: 'image', orderable: false, searchable: false, render: function(data, type, row) {
                if (!data) return '<span class="text-muted">—</span>';
                var src = '{{ asset('storage') }}' + '/' + data;
                return '<img src="' + src + '" alt="Image" style="width:42px;height:42px;object-fit:cover;border-radius:4px;">';
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
        var form = $('#categoryForm');
        form[0].reset();
        $('#categoryId').val('');
        $('#modalTitle').text('Add New Category');
        form.attr('action', "{{ route('admin.categories.store') }}");
        form.attr('method', 'POST');
        $('#method-field').remove(); // Remove method spoofing for add
        $('#catImagePreviewContainer').hide();
    });

    // Edit category
    $('body').on('click', '.edit', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#modalTitle').text('Edit Category');
        $('#categoryModal').modal('show');
        $.ajax({
            url: "{{ url('admin/categories') }}/" + id + "/edit",
            type: 'GET',
            success: function(data) {
                var form = $('#categoryForm');
                form.attr('action', "{{ url('admin/categories') }}/" + id);
                form.attr('method', 'POST');
                // Remove any previous method spoofing
                $('#method-field').remove();
                // Add method spoofing for PUT request
                form.prepend('<input type="hidden" name="_method" value="PUT" id="method-field">');
                // Set form data
                $('#categoryId').val(id);
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#is_active').prop('checked', data.is_active == 1);
                if (data.image) {
                    $('#catImagePreview').attr('src', '{{ asset('storage') }}' + '/' + data.image);
                    $('#catImagePreviewContainer').show();
                } else {
                    $('#catImagePreviewContainer').hide();
                }
            },
            error: function(xhr) {
                console.error('Edit Error:', xhr.responseText);
                $('#categoryModal').modal('hide');
                toastr.error('Failed to load category data');
            }
        });
    });

    // Reset form when modal is hidden
    $('#categoryModal').on('hidden.bs.modal', function () {
        var form = $('#categoryForm');
        form.attr('action', "{{ route('admin.categories.store') }}");
        form.attr('method', 'POST');
        $('#method-field').remove(); // Remove method spoofing for add
        form[0].reset();
        $('#categoryId').val('');
        $('#modalTitle').text('Add New Category');
        // Clear validation errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        $('#catImagePreviewContainer').hide();
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
                var isEdit = form.find('input[name="_method"]').val() === 'PUT';
                /*if (isEdit) {
                    var editedId = $('#categoryId').val();
                    if (table && table.row('#' + editedId).length) {
                        table.row('#' + editedId).invalidate().draw(false);
                    } else if (table && table.ajax) {
                        table.ajax.reload(null, false);
                    } else if (table && table.draw) {
                        table.draw(false);
                    }
                } else {
                    if (table && table.ajax) {
                        table.ajax.reload();
                    } else if (table && table.draw) {
                        table.draw(true);
                    }
                }*/
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

    // Preview selected image
    $('#image').on('change', function(e) {
        var file = e.target.files && e.target.files[0];
        if (!file) { $('#catImagePreviewContainer').hide(); return; }
        var reader = new FileReader();
        reader.onload = function(ev) {
            $('#catImagePreview').attr('src', ev.target.result);
            $('#catImagePreviewContainer').show();
        };
        reader.readAsDataURL(file);
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
        
        $.ajax({
            url: "{{ url('admin/categories') }}/" + deleteId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                toastr.success('Category deleted successfully');
                table.draw();  // Changed to table.draw() for proper refresh
            },
            error: function(xhr) {
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