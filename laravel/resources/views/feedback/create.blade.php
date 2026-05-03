<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Share Your Feedback
        </h2>
    </x-slot>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-comment-dots me-2"></i>Share Your Feedback</h3>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <p class="text-muted mb-4">We value your opinion! Please share your experience with our Daily Activity Tracker.</p>
                    
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
                        
                        <div class="mb-4">
                            <label for="message" class="form-label">Your Feedback <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            <div class="form-text">Share your thoughts, suggestions, or experiences (max 1000 characters)</div>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ url('/') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to Home
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                            </button>
                        </div>
                    </form>
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
    font-size: 1.2rem;
    padding: 0.5rem 0.75rem;
}

.rating-container .btn i {
    transition: transform 0.2s ease;
}

.rating-container .btn:hover i {
    transform: scale(1.2);
}
</style>
</x-app-layout>
