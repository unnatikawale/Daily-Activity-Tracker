<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Activity Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $totalCount }}</h4>
                                    <p class="card-text">Total Activities</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-tasks fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $completedCount }}</h4>
                                    <p class="card-text">Completed</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $totalCount - $completedCount }}</h4>
                                    <p class="card-text">Pending</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Navigation and Add Activity -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-day me-2"></i>
                                Activities for {{ Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}
                            </h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard', ['date' => Carbon\Carbon::parse($selectedDate)->subDay()->format('Y-m-d')]) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                                <a href="{{ route('dashboard', ['date' => Carbon\Carbon::today()->format('Y-m-d')]) }}" 
                                   class="btn btn-outline-primary">
                                    Today
                                </a>
                                <a href="{{ route('dashboard', ['date' => Carbon\Carbon::parse($selectedDate)->addDay()->format('Y-m-d')]) }}" 
                                   class="btn btn-outline-secondary">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                            <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#addActivityModal">
                                <i class="fas fa-plus me-1"></i> Add Activity
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($activities->count() > 0)
                        <div class="list-group">
                            @foreach($activities as $activity)
                                <div class="list-group-item {{ $activity->completed ? 'list-group-item-success' : '' }}">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input activity-checkbox" 
                                                       type="checkbox" 
                                                       id="activity-{{ $activity->id }}"
                                                       data-activity-id="{{ $activity->id }}"
                                                       {{ $activity->completed ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="{{ $activity->completed ? 'text-decoration-line-through text-muted' : '' }}">
                                                <h6 class="mb-1">{{ $activity->title }}</h6>
                                                @if($activity->description)
                                                    <p class="mb-0 text-muted small">{{ $activity->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="editActivity({{ $activity->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteActivity({{ $activity->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No activities for this day</h5>
                            <p class="text-muted">Start by adding your first activity!</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addActivityModal">
                                <i class="fas fa-plus me-1"></i> Add Your First Activity
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Activity Modal -->
    <div class="modal fade" id="addActivityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('activities.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Activity Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" required 
                                   value="{{ old('title') }}" placeholder="Enter activity title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Enter activity description (optional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="activity_date" class="form-label">Date</label>
                            <input type="date" class="form-control @error('activity_date') is-invalid @enderror" 
                                   id="activity_date" name="activity_date" 
                                   value="{{ $selectedDate }}" required>
                            @error('activity_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Activity Modal (will be populated dynamically) -->
    <div class="modal fade" id="editActivityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editActivityForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Activity Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_activity_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="edit_activity_date" name="activity_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle activity completion
        document.querySelectorAll('.activity-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const activityId = this.dataset.activityId;
                const listItem = this.closest('.list-group-item');
                const activityContent = listItem.querySelector('.col > div');
                
                fetch(`/activities/${activityId}/toggle-complete`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.completed) {
                            listItem.classList.add('list-group-item-success');
                            activityContent.classList.add('text-decoration-line-through', 'text-muted');
                        } else {
                            listItem.classList.remove('list-group-item-success');
                            activityContent.classList.remove('text-decoration-line-through', 'text-muted');
                        }
                        
                        // Update stats
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        // Edit activity
        function editActivity(activityId) {
            fetch(`/activities/${activityId}/edit`)
                .then(response => response.text())
                .then(html => {
                    // Parse the HTML to extract form data
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extract activity data
                    const title = doc.querySelector('input[name="title"]').value;
                    const description = doc.querySelector('textarea[name="description"]').value;
                    const date = doc.querySelector('input[name="activity_date"]').value;
                    
                    // Populate edit form
                    document.getElementById('edit_title').value = title;
                    document.getElementById('edit_description').value = description;
                    document.getElementById('edit_activity_date').value = date;
                    
                    // Set form action
                    document.getElementById('editActivityForm').action = `/activities/${activityId}`;
                    
                    // Show modal
                    new bootstrap.Modal(document.getElementById('editActivityModal')).show();
                });
        }

        // Delete activity
        function deleteActivity(activityId) {
            if (confirm('Are you sure you want to delete this activity?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/activities/${activityId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
