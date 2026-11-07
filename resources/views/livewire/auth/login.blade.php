<div>
    <h1>Log in</h1>

    <form wire:submit="login" class="card">
        <label for="email">Email</label>
        <input id="email" type="email" wire:model="email" required autofocus autocomplete="email">
        @error('email') <p class="error">{{ $message }}</p> @enderror

        <label for="password">Password</label>
        <input id="password" type="password" wire:model="password" required autocomplete="current-password">
        @error('password') <p class="error">{{ $message }}</p> @enderror

        <label class="inline"><input type="checkbox" wire:model="remember"> Remember me</label>

        <button type="submit">Log in</button>
    </form>

    <p class="meta">No account? <a href="{{ route('register') }}" wire:navigate>Register</a></p>
</div>
