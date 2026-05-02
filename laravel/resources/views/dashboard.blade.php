<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Activity Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Success Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Error Messages -->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="card-title fw-bold mb-0">{{ $totalCount }}</h3>
                                    <p class="card-text mb-0">Total Activities</p>
                                </div>
                                <div>
                                    <i class="fas fa-tasks fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="card-title fw-bold mb-0">{{ $completedCount }}</h3>
                                    <p class="card-text mb-0">Completed</p>
                                </div>
                                <div>
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="card-title fw-bold mb-0">{{ $totalCount - $completedCount }}</h3>
                                    <p class="card-text mb-0">Pending</p>
                                </div>
                                <div>
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Navigation and Add Activity -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-day me-2"></i>
                                {{ Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}
                            </h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard', ['date' => Carbon\Carbon::parse($selectedDate)->subDay()->format('Y-m-d')]) }}" 
                                   class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-chevron-left"></i> Prev
                                </a>
                                <a href="{{ route('dashboard', ['date' => Carbon\Carbon::today()->format('Y-m-d')]) }}" 
                                   class="btn btn-light btn-sm">
                                    Today
                                </a>
                                <a href="{{ route('dashboard', ['date' => Carbon\Carbon::parse($selectedDate)->addDay()->format('Y-m-d')]) }}" 
                                   class="btn btn-outline-light btn-sm">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                            <button type="button" class="btn btn-light ms-2 btn-sm" data-bs-toggle="modal" data-bs-target="#addActivityModal">
                                <i class="fas fa-plus me-1"></i> Add
                            </button>
                            <button type="button" class="btn btn-light ms-1 btn-sm" data-bs-toggle="modal" data-bs-target="#copyActivitiesModal">
                                <i class="fas fa-copy me-1"></i> Copy
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
                        <div class="text-center py-4">
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Add Activities
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('activities.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div id="activities-container">
                            <!-- First Activity -->
                            <div class="activity-item mb-4 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 fw-semibold">Activity 1</h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeActivity(this)" style="display:none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" name="activities[0][title]" required placeholder="Enter activity title">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date</label>
                                        <input type="date" class="form-control" name="activities[0][activity_date]" value="{{ $selectedDate }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="activities[0][description]" rows="2" placeholder="Enter activity description (optional)"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addNewActivity()">
                            <i class="fas fa-plus me-1"></i>Add Another Activity
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add All Activities
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Copy Activities Modal -->
    <div class="modal fade" id="copyActivitiesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-copy me-2"></i>Copy Activities
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="source-date" class="form-label">Select Date to Copy From</label>
                        <select class="form-select" id="source-date" onchange="loadActivitiesForDate()">
                            <option value="">Choose a date...</option>
                        </select>
                    </div>
                    
                    <div id="activities-to-copy" class="d-none">
                        <h6 class="mb-3">Select Activities to Copy:</h6>
                        <div id="activities-list" class="list-group mb-3" style="max-height: 300px; overflow-y: auto;">
                            <!-- Activities will be loaded here -->
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleAllActivities()">
                            <label class="form-check-label" for="selectAll">
                                Select All Activities
                            </label>
                        </div>
                    </div>
                    
                    <div id="no-activities" class="alert alert-info d-none">
                        <i class="fas fa-info-circle me-2"></i>
                        No activities found for the selected date.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="copySelectedBtn" onclick="copySelectedActivities()" disabled>
                        <i class="fas fa-copy me-1"></i> Copy Selected Activities
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Activity Modal (will be populated dynamically) -->
    <div class="modal fade" id="editActivityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Activity
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
        let activityCount = 1;
        let availableDates = [];
        let activitiesByDate = {};

        // Initialize copy modal on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, starting initialization...');
            console.log('Current user ID: {{ Auth::id() }}');
            console.log('Is user authenticated: {{ Auth::check() ? 'yes' : 'no' }}');
            loadAvailableDates();
        });

        // Load available dates with activities
        function loadAvailableDates() {
            console.log('Loading available dates...');
            
            // First test authentication
            fetch('/test-auth', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Auth test response status:', response.status);
                return response.json();
            })
            .then(authData => {
                console.log('Auth test data:', authData);
                
                if (!authData.authenticated) {
                    throw new Error('User not authenticated');
                }
                
                // Now try to load the dates with proper credentials
                return fetch('/activities/dates-with-activities', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin' // Important for cookies
                });
            })
            .then(response => {
                console.log('Dates response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.error) {
                    throw new Error(data.error);
                }
                availableDates = data.dates || [];
                activitiesByDate = data.activities || {};
                populateDateDropdown();
            })
            .catch(error => {
                console.error('Error loading dates:', error);
                console.error('Full error object:', error);
                
                // Try the test route as fallback
                console.log('Trying fallback route...');
                fetch('/activities-dates-test', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Fallback response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`Fallback HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Fallback received data:', data);
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    availableDates = data.dates || [];
                    activitiesByDate = data.activities || {};
                    populateDateDropdown();
                })
                .catch(fallbackError => {
                    console.error('Fallback also failed:', fallbackError);
                    
                    // Show error message to user
                    const select = document.getElementById('source-date');
                    select.innerHTML = '<option value="">Error loading dates. Please refresh.</option>';
                    
                    // If it's an auth error, suggest reloading
                    if (error.message.includes('401') || error.message.includes('not authenticated')) {
                        select.innerHTML = '<option value="">Please refresh the page and login again.</option>';
                    }
                });
            });
        }

        // Populate date dropdown
        function populateDateDropdown() {
            console.log('Populating dropdown with dates:', availableDates);
            const select = document.getElementById('source-date');
            const selectedDate = '{{ $selectedDate }}';
            
            // Clear existing options except the default one
            select.innerHTML = '<option value="">Choose a date...</option>';
            
            if (availableDates.length === 0) {
                console.log('No dates available');
                select.innerHTML = '<option value="">No activities found on other dates</option>';
                return;
            }
            
            availableDates.forEach(date => {
                if (date !== selectedDate) {
                    const option = document.createElement('option');
                    option.value = date;
                    option.textContent = formatDate(date);
                    select.appendChild(option);
                }
            });
            
            console.log('Dropdown populated successfully');
        }

        // Format date for display
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                weekday: 'short', 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }

        // Load activities for selected date
        function loadActivitiesForDate() {
            const selectedDate = document.getElementById('source-date').value;
            const activitiesContainer = document.getElementById('activities-to-copy');
            const activitiesList = document.getElementById('activities-list');
            const noActivities = document.getElementById('no-activities');
            
            if (!selectedDate) {
                activitiesContainer.classList.add('d-none');
                noActivities.classList.add('d-none');
                return;
            }
            
            const activities = activitiesByDate[selectedDate] || [];
            
            if (activities.length === 0) {
                activitiesContainer.classList.add('d-none');
                noActivities.classList.remove('d-none');
                return;
            }
            
            activitiesList.innerHTML = '';
            activities.forEach((activity, index) => {
                const activityItem = document.createElement('div');
                activityItem.className = 'list-group-item';
                activityItem.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input activity-checkbox" type="checkbox" value="${activity.id}" id="activity-${index}">
                        <label class="form-check-label w-100" for="activity-${index}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">${activity.title}</h6>
                                    ${activity.description ? `<p class="mb-0 text-muted small">${activity.description}</p>` : ''}
                                </div>
                                <span class="badge bg-secondary">${formatDate(activity.activity_date)}</span>
                            </div>
                        </label>
                    </div>
                `;
                activitiesList.appendChild(activityItem);
            });
            
            activitiesContainer.classList.remove('d-none');
            noActivities.classList.add('d-none');
            updateCopyButton();
        }

        // Toggle all activities selection
        function toggleAllActivities() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('#activities-list .activity-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            updateCopyButton();
        }

        // Update copy button state
        function updateCopyButton() {
            const checkboxes = document.querySelectorAll('#activities-list .activity-checkbox:checked');
            const copyBtn = document.getElementById('copySelectedBtn');
            
            copyBtn.disabled = checkboxes.length === 0;
            copyBtn.innerHTML = checkboxes.length > 0 
                ? `<i class="fas fa-copy me-1"></i> Copy ${checkboxes.length} Activity${checkboxes.length > 1 ? 's' : ''}`
                : '<i class="fas fa-copy me-1"></i> Copy Selected Activities';
        }

        // Copy selected activities
        function copySelectedActivities() {
            const checkboxes = document.querySelectorAll('#activities-list .activity-checkbox:checked');
            const activityIds = Array.from(checkboxes).map(cb => cb.value);
            
            if (activityIds.length === 0) {
                return;
            }
            
            const formData = new FormData();
            activityIds.forEach(id => {
                formData.append('activity_ids[]', id);
            });
            formData.append('target_date', '{{ $selectedDate }}');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/activities/copy', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('copyActivitiesModal')).hide();
                    location.reload();
                } else {
                    alert('Error copying activities: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error copying activities. Please try again.');
            });
        }

        // Update checkboxes when individual activities are selected
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('activity-checkbox') && e.target.closest('#activities-list')) {
                updateCopyButton();
                
                // Update select all checkbox
                const allCheckboxes = document.querySelectorAll('#activities-list .activity-checkbox');
                const checkedCheckboxes = document.querySelectorAll('#activities-list .activity-checkbox:checked');
                const selectAll = document.getElementById('selectAll');
                
                selectAll.checked = allCheckboxes.length === checkedCheckboxes.length;
                selectAll.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
            }
        });

        // Add new activity form
        function addNewActivity() {
            activityCount++;
            const container = document.getElementById('activities-container');
            const newActivity = document.createElement('div');
            newActivity.className = 'activity-item mb-4 p-3 border rounded';
            newActivity.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-semibold">Activity ${activityCount}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeActivity(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="activities[${activityCount - 1}][title]" required placeholder="Enter activity title">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="activities[${activityCount - 1}][activity_date]" value="{{ $selectedDate }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="activities[${activityCount - 1}][description]" rows="2" placeholder="Enter activity description (optional)"></textarea>
                </div>
            `;
            container.appendChild(newActivity);
            updateRemoveButtons();
        }

        // Remove activity form
        function removeActivity(button) {
            const activityItem = button.closest('.activity-item');
            activityItem.remove();
            updateActivityNumbers();
            updateRemoveButtons();
        }

        // Update activity numbers
        function updateActivityNumbers() {
            const activities = document.querySelectorAll('.activity-item');
            activities.forEach((activity, index) => {
                const titleElement = activity.querySelector('h6');
                titleElement.textContent = `Activity ${index + 1}`;
                
                // Update input names to maintain sequential order
                const inputs = activity.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name && name.includes('activities[')) {
                        const newName = name.replace(/activities\[\d+\]/, `activities[${index}]`);
                        input.setAttribute('name', newName);
                    }
                });
            });
            activityCount = activities.length;
        }

        // Update remove buttons visibility
        function updateRemoveButtons() {
            const activities = document.querySelectorAll('.activity-item');
            activities.forEach((activity, index) => {
                const removeButton = activity.querySelector('button[onclick="removeActivity(this)"]');
                if (removeButton) {
                    removeButton.style.display = activities.length > 1 ? 'block' : 'none';
                }
            });
        }

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

    <style>
        .card {
            transition: box-shadow 0.2s ease;
        }
        
        .card:hover {
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }
        
        .btn {
            transition: transform 0.1s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
    </style>
</x-app-layout>
