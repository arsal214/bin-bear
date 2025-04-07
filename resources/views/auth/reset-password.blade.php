<x-guest-layout>
    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">
        <!-- card -->
        <form class="login-form needs-validation" method="POST" action="{{ route('password.store') }}" novalidate>
            @csrf
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                            <img src="{{ asset('assets/images/logo_icon.svg') }}" class="h-48px" alt="">
                        </div>
                        <h5 class="mb-0">Reset Your Password</h5>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input id="email" class="form-control" type="email" name="email"
                                   value="{{ old('email', $request->email) }}" required autofocus
                                   autocomplete="username" readonly="readonly"/>
                            <div class="form-control-feedback-icon">
                                <i class="ph-user-circle text-muted"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="password" name="password" class="form-control" placeholder="•••••••••••"
                                   required autocomplete="current-password">
                            <div class="form-control-feedback-icon">
                                <i class="ph-lock text-muted"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="password" name="password_confirmation" class="form-control"
                                   placeholder="•••••••••••"
                                   required autocomplete="current-password">
                            <div class="form-control-feedback-icon">
                                <i class="ph-lock text-muted"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                    </div>

                </div>
        </form>
    </div>

    <!-- /content area -->

    @push('scripts')
        <script src="{{ asset('assets/demo/pages/form_validation_styles.js') }}"></script>
    @endpush
</x-guest-layout>
