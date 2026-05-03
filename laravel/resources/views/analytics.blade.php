<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analytics Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Period Selector -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>Activity Analytics
                            </h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('analytics', ['period' => '7']) }}" 
                                   class="btn btn-outline-primary {{ $period == '7' ? 'active' : '' }}">7 Days</a>
                                <a href="{{ route('analytics', ['period' => '30']) }}" 
                                   class="btn btn-outline-primary {{ $period == '30' ? 'active' : '' }}">30 Days</a>
                                <a href="{{ route('analytics', ['period' => '90']) }}" 
                                   class="btn btn-outline-primary {{ $period == '90' ? 'active' : '' }}">90 Days</a>
                            </div>
                            <button type="button" class="btn btn-outline-secondary ms-2" onclick="recalculateStreak()">
                                <i class="fas fa-sync-alt me-1"></i>Recalculate Streak
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="card-title fw-bold mb-0">{{ $totalActivities }}</h3>
                                    <p class="card-text mb-0">Total Activities</p>
                                </div>
                                <div>
                                    <i class="fas fa-tasks fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="card-title fw-bold mb-0">{{ $completedActivities }}</h3>
                                    <p class="card-text mb-0">Completed</p>
                                </div>
                                <div>
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="card-title fw-bold mb-0">{{ $pendingActivities }}</h3>
                                    <p class="card-text mb-0">Pending</p>
                                </div>
                                <div>
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="card-title fw-bold mb-0">{{ $completionRate }}%</h3>
                                    <p class="card-text mb-0">Completion Rate</p>
                                </div>
                                <div>
                                    <i class="fas fa-percentage fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Streak Card -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card bg-gradient-primary text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-2">
                                        <i class="fas fa-fire me-2"></i>Current Streak
                                    </h5>
                                    <h2 class="fw-bold mb-0">{{ $streak }} Days</h2>
                                    <p class="card-text mb-0">Consecutive days with completed activities</p>
                                    <small class="opacity-75">
                                        <i class="fas fa-trophy me-1"></i>Best: {{ $longestStreak }} days
                                    </small>
                                </div>
                                <div>
                                    <i class="fas fa-fire-alt fa-4x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body py-4">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-lightbulb me-2 text-warning"></i>Personalized Tips
                            </h5>
                            @foreach($tips as $tip)
                                <div class="alert alert-info mb-2">
                                    <i class="fas fa-info-circle me-2"></i>{{ $tip }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-md-8 mb-3">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Daily Activity Overview
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="dailyChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-pie me-2"></i>Activity Types
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="typeChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Progress Chart -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>Weekly Completion Rate
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="weeklyChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Range Info -->
            <div class="card">
                <div class="card-body text-center text-muted">
                    <small>
                        <i class="fas fa-calendar me-2"></i>
                        Showing data from {{ $startDate }} to {{ $endDate }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Debug: Log the data
        console.log('Daily Data:', @json($dailyData));
        console.log('Total Activities:', {{ $totalActivities }});
        console.log('Completed Activities:', {{ $completedActivities }});
        console.log('Pending Activities:', {{ $pendingActivities }});

        // Daily Activity Chart
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        const dailyData = @json($dailyData);
        
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: dailyData.map(d => d.date),
                datasets: [
                    {
                        label: 'Completed',
                        data: dailyData.map(d => d.completed),
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pending',
                        data: dailyData.map(d => d.pending),
                        backgroundColor: 'rgba(255, 193, 7, 0.8)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Activity Type Pie Chart
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        const activityTypes = @json($activityTypes);
        
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Work', 'Exercise', 'Personal', 'Learning', 'Other'],
                datasets: [{
                    data: [
                        activityTypes.work,
                        activityTypes.exercise,
                        activityTypes.personal,
                        activityTypes.learning,
                        activityTypes.other
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(201, 203, 207, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(201, 203, 207, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Weekly Completion Rate Chart
        const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
        const weeklyData = @json($weeklyData);
        
        new Chart(weeklyCtx, {
            type: 'line',
            data: {
                labels: weeklyData.map(w => w.week),
                datasets: [{
                    label: 'Completion Rate (%)',
                    data: weeklyData.map(w => w.completion_rate),
                    fill: true,
                    backgroundColor: 'rgba(23, 162, 184, 0.2)',
                    borderColor: 'rgba(23, 162, 184, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(23, 162, 184, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(23, 162, 184, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });

        // Recalculate streak function
        function recalculateStreak() {
            if (!confirm('This will recalculate your streak from all historical data. Continue?')) {
                return;
            }

            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Recalculating...';
            btn.disabled = true;

            fetch('/recalculate-streak', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Streak recalculated successfully! Current: ' + data.current_streak + ' days, Longest: ' + data.longest_streak + ' days');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                alert('Error recalculating streak: ' + error.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }
    </script>
</x-app-layout>
