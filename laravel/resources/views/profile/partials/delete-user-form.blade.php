<div class="delete-account-form">
    <div class="alert alert-danger d-flex align-items-start">
        <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
        <div>
            <strong class="d-block mb-1">{{ __('Warning: This action cannot be undone') }}</strong>
            <small class="text-muted">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
            </small>
        </div>
    </div>

    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        <i class="fas fa-trash-alt me-2"></i>{{ __('Delete Account') }}
    </button>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmUserDeletionModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ __('Confirm Account Deletion') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('Are you sure you want to delete your account? This action is permanent and cannot be undone.') }}
                        </div>
                        
                        <p class="text-muted">
                            {{ __('Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="mt-3">
                            <label for="deletePassword" class="form-label fw-semibold">
                                <i class="fas fa-key me-1 text-danger"></i>
                                {{ __('Password') }}
                            </label>
                            <input type="password" class="form-control" id="deletePassword" name="password" 
                                   placeholder="{{ __('Enter your password') }}" required>
                            @error('userDeletion.password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-2"></i>{{ __('Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
