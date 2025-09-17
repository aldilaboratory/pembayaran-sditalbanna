<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">

                {{-- Tampilkan status session (misal berhasil reset password) --}}
                @if (session('status'))
                    <div class="alert alert-success mb-4">
                    {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Username --}}
                    <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input id="username" type="text"
                            class="form-control @error('username') is-invalid @enderror"
                            name="username" value="{{ old('username') }}"
                            required autofocus autocomplete="username"
                            style="width: 100%; border-radius: 5px; border-color: rgb(183, 183, 183);">
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password"
                            style="width: 100%; border-radius: 5px; border-color: rgb(183, 183, 183);">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">Remember me</label>
                    </div>

                    <div class="text-end">
                    {{-- Jika ingin menampilkan link lupa password
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                        Forgot your password?
                        </a>
                    @endif
                    --}}
                    <button type="submit" class="btn btn-primary ms-2" style="padding: 5px 15px; background-color: rgb(4, 74, 4); color: white; border-radius: 5px;">
                        Log in
                    </button>
                    </div>

                </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</x-guest-layout>
