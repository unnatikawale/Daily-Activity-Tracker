<div class="password-form">
    <p class="text-muted mb-4">
        <i class="fas fa-shield-alt me-2"></i>
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </p>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="row g-3">
            <div class="col-12">
                <label for="update_password_current_password" class="form-label fw-semibold">
                    <i class="fas fa-key me-1 text-warning"></i>
                    {{ __('Current Password') }}
                </label>
                <input type="password" class="form-control form-control-lg" id="update_password_current_password" 
                       name="current_password" autocomplete="current-password" 
                       placeholder="{{ __('Enter your current password') }}">
                @error('updatePassword.current_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="update_password_password" class="form-label fw-semibold">
                    <i class="fas fa-lock me-1 text-warning"></i>
                    {{ __('New Password') }}
                </label>
                <input type="password" class="form-control form-control-lg" id="update_password_password" 
                       name="password" autocomplete="new-password" 
                       placeholder="{{ __('Enter new password') }}">
                @error('updatePassword.password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="update_password_password_confirmation" class="form-label fw-semibold">
                    <i class="fas fa-lock me-1 text-warning"></i>
                    {{ __('Confirm Password') }}
                </label>
                <input type="password" class="form-control form-control-lg" id="update_password_password_confirmation" 
                       name="password_confirmation" autocomplete="new-password" 
                       placeholder="{{ __('Confirm new password') }}">
                @error('updatePassword.password_confirmation')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle me-2"></i>
            <small>
                {{ __('Password should be at least 8 characters long and include a mix of letters, numbers, and symbols.') }}
            </small>
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-warning btn-lg px-4">
                <i class="fas fa-save me-2"></i>{{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success mb-0 py-2 px-3">
                    <i class="fas fa-check-circle me-2"></i>{{ __('Password updated successfully.') }}
                </div>
            @endif
        </div>
    </form>
</div>
