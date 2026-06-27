<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;padding:0.75rem 1rem;border-radius:0.5rem;font-size:0.875rem;margin-bottom:1.25rem;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="contoh@email.com">
            @error('email')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
            @error('password')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-checkbox">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">Ingat saya</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-login">
            Masuk
        </button>

        @if (Route::has('password.request'))
            <div style="text-align:center;margin-top:1rem;">
                <a href="{{ route('password.request') }}" style="font-size:0.8125rem;color:#64748b;text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#64748b'">
                    Lupa password?
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
