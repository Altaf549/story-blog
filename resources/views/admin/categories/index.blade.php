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
            <form id="categoryForm" method="POST">
                @csrf
                <input type="hidden" name="id" id="categoryId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
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

    // Reset form and open modal for adding new category
    $('#categoryModal').on('show.bs.modal', function (e) {
        $('#categoryForm')[0].reset();
        $('#categoryId').val('');
        $('#modalTitle').text('Add New Category');
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
                // Set form action for update
                $('#categoryForm').attr('action', "{{ url('admin/categories') }}/" + id);
                $('#categoryForm').attr('method', 'POST');
                
                // Add method spoofing for PUT request
                if ($('#method-field').length === 0) {
                    $('#categoryForm').prepend('<input type="hidden" name="_method" value="PUT" id="method-field">');
                }
                
                // Set form data
                $('#categoryId').val(id);
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#is_active').prop('checked', data.is_active == 1);
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
        $('#method-field').remove();
        form[0].reset();
        $('#categoryId').val('');
        $('#modalTitle').text('Add New Category');
        // Clear validation errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
    });

    // Submit form
    $('#categoryForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var url = form.attr('action') || "{{ route('admin.categories.store') }}";
        var method = form.find('input[name="_method"]').val() || 'POST';
        
        // Clear previous errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        
        $.ajax({
            url: url,
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
                $('#categoryModal').modal('hide');
                form[0].reset();
                toastr.success(response.message || 'Category saved successfully');
                table.ajax.reload();
            },
            error: function(xhr) {
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