<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            User Feedback
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Success Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Feedback Form Section -->
                <div class="col-lg-5 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-comment-dots me-2"></i>Share Your Feedback</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">We value your opinion! Please share your experience with our Daily Activity Tracker.</p>
                            
                            <form action="{{ route('feedback.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Rating <span class="text-danger">*</span></label>
                                    <div class="rating-container">
                                        <div class="btn-group" role="group">
                                            @for($i = 1; $i <= 5; $i++)
                                                <input type="radio" class="btn-check" name="rating" id="star{{ $i }}" 
                                                       value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                                <label class="btn btn-outline-warning" for="star{{ $i }}">
                                                    <i class="fas fa-star"></i>
                                                </label>
                                            @endfor
                                        </div>
                                        <small class="text-muted d-block mt-2">Click to rate from 1 to 5 stars</small>
                                    </div>
                                    @error('rating')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="message" class="form-label">Your Feedback <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                                    <div class="form-text">Share your thoughts, suggestions, or experiences (max 1000 characters)</div>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Feedback Display Section -->
                <div class="col-lg-7">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-comments me-2"></i>User Feedbacks</h5>
                        </div>
                        <div class="card-body">
                            @if($feedbacks->count() > 0)
                                <div class="feedback-list">
                                    @foreach($feedbacks as $feedback)
                                        <div class="feedback-item border-bottom pb-3 mb-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="d-flex align-items-center">
                                                    <img src="https://picsum.photos/seed/{{ Str::slug($feedback->name) }}/40/40.jpg" 
                                                         class="rounded-circle me-2" width="40" height="40" alt="{{ $feedback->name }}">
                                                    <div>
                                                        <h6 class="mb-0">{{ $feedback->name }}</h6>
                                                        <small class="text-muted">{{ $feedback->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                                <div class="stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $feedback->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="mb-2">{{ $feedback->message }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Pagination -->
                                @if($feedbacks->hasPages())
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $feedbacks->links() }}
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Feedbacks Yet</h5>
                                    <p class="text-muted">Be the first to share your experience with our Daily Activity Tracker!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .rating-container .btn-check:checked + .btn {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .rating-container .btn-check:hover + .btn {
        background-color: #ffca2c;
        border-color: #ffc107;
    }

    .rating-container .btn {
        font-size: 1.1rem;
        padding: 0.4rem 0.6rem;
    }

    .rating-container .btn i {
        transition: transform 0.2s ease;
    }

    .rating-container .btn:hover i {
        transform: scale(1.2);
    }

    .feedback-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .stars {
        font-size: 0.9rem;
    }
    </style>
</x-app-layout>
