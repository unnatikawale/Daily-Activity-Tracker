<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monthly Tracker') }}
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

            <!-- Month Selection and Add Activity -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i>
                                {{ $monthName }}
                            </h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('monthly-tracker', ['month' => Carbon\Carbon::parse($month)->subMonth()->format('Y-m')]) }}" 
                                   class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                                <a href="{{ route('monthly-tracker', ['month' => Carbon\Carbon::now()->format('Y-m')]) }}" 
                                   class="btn btn-light btn-sm">
                                    Current Month
                                </a>
                                <a href="{{ route('monthly-tracker', ['month' => Carbon\Carbon::parse($month)->addMonth()->format('Y-m')]) }}" 
                                   class="btn btn-outline-light btn-sm">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                            <button type="button" class="btn btn-light ms-2 btn-sm" data-bs-toggle="modal" data-bs-target="#addMonthlyActivityModal">
                                <i class="fas fa-plus me-1"></i> Add Activity
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Activities Table -->
            <div class="card">
                <div class="card-body">
                    {{-- Debug: Show activity count --}}
                    <div class="alert alert-info mb-3">
                        <small>
                            <strong>Debug Information:</strong><br>
                            Found {{ $monthlyActivities->count() }} monthly activities for {{ $monthName }}<br>
                            Month variable: {{ $month }}<br>
                            User ID: {{ Auth::id() ?: 'Not authenticated' }}<br>
                            <strong>Monthly Activities:</strong><br>
                            @foreach($monthlyActivities as $activity)
                                • {{ $activity->title }} (ID: {{ $activity->id }})
                                @if(isset($activity->is_sample) && $activity->is_sample)
                                    <span class="badge bg-warning">Sample</span>
                                @else
                                    <span class="badge bg-success">Monthly</span>
                                @endif
                                <br>
                            @endforeach
                            @if($monthlyActivities->count() === 0)
                                <br>
                                <strong>No monthly activities found!</strong><br>
                                Add activities using the "Add Activity" button.
                            @endif
                        </small>
                    </div>
                    
                    @if($monthlyActivities->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="min-width: 150px;">Activity</th>
                                        @foreach($days as $day)
                                            <th class="text-center {{ $day['is_weekend'] ? 'bg-light' : '' }}" 
                                                style="min-width: 40px; font-size: 12px;">
                                                <div>{{ $day['day'] }}</div>
                                                <small class="text-muted">{{ $day['weekday'] }}</small>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyActivities as $activity)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $activity->title }}</div>
                                                @if($activity->description)
                                                    <small class="text-muted">{{ $activity->description }}</small>
                                                @endif
                                            </td>
                                            @foreach($days as $day)
                                                <td class="text-center {{ $day['is_weekend'] ? 'bg-light' : '' }}">
                                                    <div class="form-check form-check-inline">
                                                        @php
                                                    $activityKey = is_numeric($activity->id) ? $activity->id : 'daily_' . $activity->id;
                                                    $dayNumber = $day['day'];
                                                @endphp
                                                <input class="form-check-input monthly-checkbox" 
                                                               type="checkbox" 
                                                               data-activity-id="{{ $activity->id }}"
                                                               data-day="{{ $day['day'] }}"
                                                               data-date="{{ $day['date']->format('Y-m-d') }}"
                                                               data-activity-title="{{ $activity->title }}"
                                                               {{ isset($completionData[$activityKey][$dayNumber]) && $completionData[$activityKey][$dayNumber] ? 'checked' : '' }}
                                                    >
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No monthly activities set</h5>
                            <p class="text-muted">Add activities to track throughout the month!</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMonthlyActivityModal">
                                <i class="fas fa-plus me-1"></i> Add Your First Monthly Activity
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Monthly Activity Modal -->
    <div class="modal fade" id="addMonthlyActivityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Add Monthly Activity
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('monthly-activities.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Activity Title</label>
                            <input type="text" class="form-control" name="title" required 
                                   placeholder="Enter activity title">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" 
                                      placeholder="Enter activity description (optional)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="month" class="form-label">Month</label>
                            <input type="month" class="form-control" name="month" 
                                   value="{{ $month }}" required>
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

    <script>
        // Toggle monthly activity completion
        document.querySelectorAll('.monthly-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const activityId = this.dataset.activityId;
                const day = this.dataset.day;
                const date = this.dataset.date;
                const title = this.dataset.activityTitle;
                const completed = this.checked;
                
                // Always use the toggle endpoint for both checking and unchecking
                const formData = new FormData();
                formData.append('title', title);
                formData.append('activity_date', date);
                formData.append('completed', completed ? 'true' : 'false');
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                console.log('Submitting:', {
                    title: title,
                    date: date,
                    completed: completed,
                    formDataEntries: Array.from(formData.entries())
                });
                
                fetch('/activities/toggle-by-title-date', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        // Revert checkbox if failed
                        this.checked = !this.checked;
                        alert('Error updating activity: ' + (data.message || 'Unknown error'));
                        console.error('API Error:', data);
                    }
                })
                .catch(error => {
                    console.error('Network Error:', error);
                    // Revert checkbox if failed
                    this.checked = !this.checked;
                    alert('Error updating activity. Please try again.');
                });
            });
        });

        // Show error modal function
        function showErrorModal(message) {
            const errorModal = document.createElement('div');
            errorModal.className = 'modal fade';
            errorModal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>Duplicate Activity
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>${message}</p>
                            <p class="text-muted small">Would you like to try adding a different activity?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Try Different Activity</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(errorModal);
            const modal = new bootstrap.Modal(errorModal);
            modal.show();
            
            // Remove modal from DOM after it's hidden
            errorModal.addEventListener('hidden.bs.modal', function() {
                document.body.removeChild(errorModal);
            });
        }

        // Handle monthly activity form submission
        document.querySelector('#addMonthlyActivityModal form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Adding...';
            
            // Use test route for non-authenticated users
            const isTestRoute = window.location.pathname.includes('/test-monthly-tracker');
            const url = isTestRoute ? '/test-monthly-activities/store' : '/monthly-activities/store';
            
            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Close modal and reload page
                    bootstrap.Modal.getInstance(document.getElementById('addMonthlyActivityModal')).hide();
                    console.log('Reloading page...');
                    location.reload();
                } else {
                    // Show error message in a better way
                    showErrorModal(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding activity. Please try again.');
            })
            .finally(() => {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    </script>
</x-app-layout>
