@extends('admin.layouts.app')

@section('title', 'Manage Stories')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Stories</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#storyModal" id="btnAddStory">Add Story</button>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped" id="storiesTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Banner</th>
              <th>Author</th>
              <th>Category</th>
              <th>Status</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          @foreach($stories as $story)
            <tr data-id="{{ $story->id }}"
                data-title="{{ $story->title }}"
                data-content='@json($story->content)'
                data-category_id="{{ $story->category_id }}"
                data-banner_image="{{ $story->banner_image }}">
              <td>{{ $story->id }}</td>
              <td>{{ $story->title }}</td>
              <td>
                @if($story->banner_image)
                  <img src="{{ asset('storage/' . $story->banner_image) }}" alt="Banner" style="width: 80px; height: 45px; object-fit: cover;">
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td>{{ optional($story->user)->name }}</td>
              <td>{{ optional($story->category)->name }}</td>
              <td>
                <form action="{{ route('admin.stories.status', $story) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PATCH')
                  <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="pending" @selected($story->status==='pending')>Pending</option>
                    <option value="approved" @selected($story->status==='approved')>Approved</option>
                    <option value="rejected" @selected($story->status==='rejected')>Rejected</option>
                  </select>
                </form>
              </td>
              <td>{{ $story->created_at->format('Y-m-d') }}</td>
              <td>
                <button class="btn btn-sm btn-secondary btn-edit" data-bs-toggle="modal" data-bs-target="#storyModal">Edit</button>
                <form action="{{ route('admin.stories.destroy', $story) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this story?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      <div class="mt-3">
        {{ $stories->links() }}
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="storyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="storyForm" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="storyModalLabel">Add Story</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Title</label>
              <input type="text" class="form-control" name="title" id="storyTitle" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Category</label>
              <select class="form-select" name="category_id" id="storyCategory">
                <option value="">-- None --</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Banner Image</label>
              <input type="file" class="form-control" name="banner_image" id="storyBannerImage" accept="image/*">
              <div class="form-text">Recommended aspect ratio ~16:9. Max 2MB.</div>
              <div class="mt-2" id="bannerPreviewContainer" style="display:none;">
                <img id="bannerPreview" src="#" alt="Preview" style="width: 160px; height: 90px; object-fit: cover; border: 1px solid #ddd;" />
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Content</label>
              <textarea class="form-control" name="content" id="storyContent" rows="8"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>
  <script>
    $(function() {
      if ($.fn.DataTable) {
        $('#storiesTable').DataTable();
      }

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

      // Ensure Summernote CSS is available (secondary source if primary fails)
      setTimeout(function() {
        if (!document.querySelector('link[href*="summernote"], link[href*="Summernote"]')) {
          loadStyle('https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs5.min.css');
        }
      }, 0);

      let summernoteInitialized = false;
      function initSummernote() {
        if (summernoteInitialized) return;
        $('#storyContent').summernote({
          placeholder: 'Write your story...',
          height: 250
        });
        summernoteInitialized = true;
      }

      if ($.fn.summernote) {
        initSummernote();
      } else {
        // Fallback loader for Summernote if primary CDN failed
        loadScript(
          'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs5.min.js',
          function() { if ($.fn.summernote) { initSummernote(); } },
          function() {
            // Try another CDN for bs5 build
            loadScript(
              'https://unpkg.com/summernote@0.8.18/dist/summernote-bs5.min.js',
              function() { if ($.fn.summernote) { initSummernote(); } },
              function() {
                // Final fallback: Summernote Lite (no Bootstrap dependency)
                loadStyle('https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css');
                loadScript(
                  'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js',
                  function() { if ($.fn.summernote) { initSummernote(); } }
                );
              }
            );
          }
        );
      }

      const storeUrl = "{{ route('admin.stories.store') }}";
      let modalMode = 'create';
      let editPayload = { id: null, title: '', content: '', categoryId: '', bannerImage: '' };

      // Ensure Summernote content is submitted with the form
      $('#storyForm').on('submit', function() {
        const htmlContent = $('#storyContent').summernote('code');
        $('#storyContent').val(htmlContent);
      });

      $('#btnAddStory').on('click', function() {
        modalMode = 'create';
        editPayload = { id: null, title: '', content: '', categoryId: '' };
        $('#storyModal').modal('show');
      });

      $('#storiesTable').on('click', '.btn-edit', function() {
        const row = $(this).closest('tr');
        modalMode = 'edit';
        editPayload.id = row.data('id');
        editPayload.title = row.data('title');
        editPayload.content = (function() {
          try { return JSON.parse(row.attr('data-content')); } catch(e) { return row.attr('data-content') || ''; }
        })();
        editPayload.categoryId = row.data('category_id');
        editPayload.bannerImage = row.data('banner_image') || '';
        $('#storyModal').modal('show');
      });

      $('#storyModal').on('shown.bs.modal', function() {
        if (!summernoteInitialized && $.fn.summernote) {
          initSummernote();
        }
        if (modalMode === 'create') {
          $('#storyModalLabel').text('Add Story');
          $('#storyForm').attr('action', storeUrl).attr('method', 'POST');
          $('#storyForm input[name=_method]').remove();
          $('#storyTitle').val('');
          $('#storyCategory').val('');
          $('#storyBannerImage').val('');
          $('#bannerPreviewContainer').hide();
          if ($.fn.summernote) {
            $('#storyContent').summernote('code', '');
            $('#storyContent').summernote('focus');
          }
        } else {
          $('#storyModalLabel').text('Edit Story');
          $('#storyForm').attr('action', `{{ url('admin/stories') }}/${editPayload.id}`).attr('method', 'POST');
          if (!$('#storyForm input[name=_method]').length) {
            $('#storyForm').append('<input type="hidden" name="_method" value="PUT">');
          } else {
            $('#storyForm input[name=_method]').val('PUT');
          }
          $('#storyTitle').val(editPayload.title);
          $('#storyCategory').val(editPayload.categoryId);
          if (editPayload.bannerImage) {
            $('#bannerPreview').attr('src', `{{ asset('storage') }}/${editPayload.bannerImage}`);
            $('#bannerPreviewContainer').show();
          } else {
            $('#bannerPreviewContainer').hide();
          }
          if ($.fn.summernote) {
            $('#storyContent').summernote('code', editPayload.content || '');
            $('#storyContent').summernote('focus');
          }
        }
      });

      $('#storyModal').on('hidden.bs.modal', function() {
        modalMode = 'create';
        editPayload = { id: null, title: '', content: '', categoryId: '', bannerImage: '' };
      });

      // Preview banner image on file select
      $('#storyBannerImage').on('change', function(e) {
        const file = e.target.files && e.target.files[0];
        if (!file) { $('#bannerPreviewContainer').hide(); return; }
        const reader = new FileReader();
        reader.onload = function(ev) {
          $('#bannerPreview').attr('src', ev.target.result);
          $('#bannerPreviewContainer').show();
        };
        reader.readAsDataURL(file);
      });
    });
  </script>
@endpush


