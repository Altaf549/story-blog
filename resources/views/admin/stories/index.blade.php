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
              <th>YouTube URL</th>
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
                data-image_id="{{ $story->image_id }}"
                data-youtube_url="{{ $story->youtube_url ?? '' }}">
              <td>{{ $story->id }}</td>
              <td>{{ $story->title }}</td>
              <td>
                @if($story->image_id)
                  <img src="https://drive.google.com/thumbnail?id={{ $story->image_id }}&sz=w200" 
                       alt="Banner" 
                       style="width: 80px; height: 45px; object-fit: cover;"
                       onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-muted\'>—</span>'">
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td>
                @if($story->youtube_url)
                  <a href="{{ $story->youtube_url }}" target="_blank" title="{{ $story->youtube_url }}">
                    <i class="fas fa-external-link-alt"></i> View
                  </a>
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
              <label class="form-label">Google Drive Image ID</label>
              <div class="input-group">
                <input type="text" class="form-control" name="image_id" id="image_id" placeholder="Enter Google Drive Image ID" required>
                <button type="button" class="btn btn-outline-secondary" id="previewImage">Preview</button>
              </div>
              <small class="text-muted">Enter the Google Drive Image ID (e.g., 1a2b3c4d5e6f7g8h9i0j)</small>
              <div class="mt-2" id="bannerPreviewContainer" style="display:none;">
                <img id="bannerPreview" src="#" alt="Preview" style="width: 160px; height: 90px; object-fit: cover; border: 1px solid #ddd;" />
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">YouTube URL</label>
              <input type="url" class="form-control" name="youtube_url" id="youtubeUrl" placeholder="https://www.youtube.com/watch?v=...">
              <div class="form-text">Optional: Link to a YouTube video related to this story</div>
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
        editPayload = {
          id: row.data('id'),
          title: row.data('title'),
          content: (function() {
            try { return JSON.parse(row.attr('data-content')); } 
            catch(e) { return row.attr('data-content') || ''; }
          })(),
          categoryId: row.data('category_id'),
          imageId: row.data('image_id') || '',
          youtubeUrl: row.data('youtube_url') || ''
        };
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
          $('#image_id').val('');
          $('#youtubeUrl').val('');
          $('#bannerPreviewContainer').hide();
          if ($.fn.summernote) {
            $('#storyContent').summernote('code', '');
            $('#storyContent').summernote('focus');
          }
        } else {
          $('#storyModalLabel').text('Edit Story');
          $('#storyForm').attr('action', `/admin/stories/${editPayload.id}`).attr('method', 'POST');
          if (!$('#storyForm input[name=_method]').length) {
            $('#storyForm').append('<input type="hidden" name="_method" value="PUT">');
          } else {
            $('#storyForm input[name=_method]').val('PUT');
          }
          $('#storyTitle').val(editPayload.title);
          $('#storyCategory').val(editPayload.categoryId);
          $('#image_id').val(editPayload.imageId);
          $('#youtubeUrl').val(editPayload.youtubeUrl);
          if (editPayload.imageId) {
            $('#bannerPreview').attr('src', 'https://drive.google.com/thumbnail?id=' + editPayload.imageId + '&sz=w1000');
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

      // Handle image preview button click
      $('#previewImage').on('click', function() {
        const imageId = $('#image_id').val().trim();
        if (imageId) {
          const previewUrl = 'https://drive.google.com/thumbnail?id=' + imageId + '&sz=w1000';
          $('#bannerPreview').attr('src', previewUrl).on('error', function() {
            alert('Could not load image. Please check the Image ID.');
            $(this).attr('src', '#');
            $('#bannerPreviewContainer').hide();
          });
          $('#bannerPreviewContainer').show();
        } else {
          alert('Please enter a valid Google Drive Image ID');
        }
      });
      
      // Auto-preview when image_id changes and has value
      $('#image_id').on('change', function() {
        const imageId = $(this).val().trim();
        if (imageId) {
          const previewUrl = 'https://drive.google.com/thumbnail?id=' + imageId + '&sz=w1000';
          $('#bannerPreview').attr('src', previewUrl)
            .on('load', function() {
              $('#bannerPreviewContainer').show();
            })
            .on('error', function() {
              $('#bannerPreviewContainer').hide();
            });
        } else {
          $('#bannerPreviewContainer').hide();
        }
      });
    });
  </script>
@endpush


