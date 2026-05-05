<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fas fa-user-circle me-2 text-primary"></i>{{ __('Profile Settings') }}
                </h2>
                <p class="text-muted mb-0">Manage your account settings and preferences</p>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Profile Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2 text-primary"></i>
                            {{ __('Profile Information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-lock me-2 text-warning"></i>
                            {{ __('Update Password') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete Account Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                            {{ __('Delete Account') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
