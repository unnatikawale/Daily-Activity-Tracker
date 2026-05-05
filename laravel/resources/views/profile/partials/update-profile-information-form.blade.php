<div class="profile-form">
    <!-- TEMP DEBUG: User ID={{ $user->id }}, Photo={{ $user->profile_photo ?? 'NULL' }} -->
    <p class="text-muted mb-4">
        <i class="fas fa-info-circle me-2"></i>
        {{ __("Update your account's profile information and email address.") }}
    </p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo Section -->
        <div class="row mb-4">
            <div class="col-12">
                <label class="form-label fw-semibold">
                    <i class="fas fa-camera me-1 text-primary"></i>
                    {{ __('Profile Photo') }}
                </label>
                <div class="d-flex align-items-center gap-4">
                    <div class="position-relative">
                        <img id="photoPreview" 
                             src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=150&background=667eea&color=fff' }}" 
                             alt="{{ __('Profile Photo') }}" 
                             class="rounded-circle border border-3 border-light shadow-sm"
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2">
                            <i class="fas fa-camera text-white" style="font-size: 12px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <input type="file" 
                               id="profile_photo" 
                               name="profile_photo" 
                               class="form-control" 
                               accept="image/*"
                               onchange="previewPhoto(event)">
                        @error('profile_photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2">
                            {{ __('Allowed formats: JPG, PNG, GIF. Maximum size: 2MB.') }}
                        </small>
                        @if($user->profile_photo)
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger mt-2" 
                                    onclick="removePhoto()">
                                <i class="fas fa-trash me-1"></i>{{ __('Remove Photo') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label fw-semibold">
                    <i class="fas fa-user me-1 text-primary"></i>
                    {{ __('Name') }}
                </label>
                <input type="text" class="form-control form-control-lg" id="name" name="name" 
                       value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" 
                       placeholder="{{ __('Enter your name') }}">
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label fw-semibold">
                    <i class="fas fa-envelope me-1 text-primary"></i>
                    {{ __('Email') }}
                </label>
                <input type="email" class="form-control form-control-lg" id="email" name="email" 
                       value="{{ old('email', $user->email) }}" required autocomplete="username" 
                       placeholder="{{ __('Enter your email') }}">
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert alert-warning mt-2 d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <small class="d-block">{{ __('Your email address is unverified.') }}</small>
                            <button form="send-verification" class="btn btn-link btn-sm p-0 text-decoration-none">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-save me-2"></i>{{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success mb-0 py-2 px-3">
                    <i class="fas fa-check-circle me-2"></i>{{ __('Saved.') }}
                </div>
            @endif
        </div>
    </form>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photoPreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

function removePhoto() {
    if (confirm('{{ __("Are you sure you want to remove your profile photo?") }}')) {
        // Create a hidden input to signal photo removal
        const form = document.querySelector('form[method="post"]');
        const removeInput = document.createElement('input');
        removeInput.type = 'hidden';
        removeInput.name = 'remove_photo';
        removeInput.value = '1';
        form.appendChild(removeInput);
        
        // Reset the preview to default avatar
        const preview = document.getElementById('photoPreview');
        const userName = document.getElementById('name').value;
        preview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&size=150&background=667eea&color=fff`;
        
        // Clear the file input
        document.getElementById('profile_photo').value = '';
        
        // Submit the form
        form.submit();
    }
}
</script>
