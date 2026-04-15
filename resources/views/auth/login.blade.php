<x-guest-layout>
    @if (session('status'))
        <div class="alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" required autofocus autocomplete="username">
            @error('email') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-input" required autocomplete="current-password">
            @error('password') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;color:#374151;">
                <input id="remember_me" type="checkbox" name="remember">
                Remember me
            </label>
        </div>

        <div class="auth-footer">
            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">Forgot your password?</a>
            @endif
            <button type="submit" class="btn btn-primary">Log in</button>
        </div>
    </form>
</x-guest-layout>
