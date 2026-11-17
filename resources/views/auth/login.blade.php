@extends('layouts.app')

@section('title', 'Administrator login')

@section('content')
    <section class="login-shell">
        <div class="login-brand">Inventory management</div>
        <h1>Welcome back</h1>
        <p class="subtitle" style="margin:0 0 22px">Sign in to manage pickleball equipment.</p>
        <div class="card">
            @if ($errors->any())
                <div class="alert alert-error" role="alert">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="field" style="margin-bottom:17px">
                    <label for="identifier">Email address or username</label>
                    <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}" autocomplete="username" required autofocus>
                    @error('identifier') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <div class="field" style="margin-bottom:17px">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                    @error('password') <span class="field-error">{{ $message }}</span> @enderror
                </div>
                <label style="display:flex;align-items:center;gap:8px;font-weight:400;margin-bottom:20px">
                    <input type="checkbox" name="remember" value="1" style="width:auto"> Remember me
                </label>
                <button class="btn" type="submit" style="width:100%">Sign in</button>
            </form>
        </div>
    </section>
@endsection
