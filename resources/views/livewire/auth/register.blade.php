<div>
    <h1>Register</h1>

    <form wire:submit="register" class="card">
        <label for="name">Name</label>
        <input id="name" wire:model="name" required autofocus autocomplete="name">
        @error('name') <p class="error">{{ $message }}</p> @enderror

        <label for="email">Email</label>
        <input id="email" type="email" wire:model="email" required autocomplete="email">
        @error('email') <p class="error">{{ $message }}</p> @enderror

        <label for="password">Password</label>
        <input id="password" type="password" wire:model="password" required autocomplete="new-password">
        @error('password') <p class="error">{{ $message }}</p> @enderror

        <label for="password_confirmation">Confirm password</label>
        <input id="password_confirmation" type="password" wire:model="password_confirmation" required autocomplete="new-password">

        <button type="submit">Create account</button>
    </form>

    <p class="meta">Already registered? <a href="{{ route('login') }}" wire:navigate>Log in</a></p>
</div>
