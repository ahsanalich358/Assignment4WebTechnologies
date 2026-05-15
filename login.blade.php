<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – To-Do App</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue: #3B5BDB; --blue-light: #e7ecff; --blue-dark: #2f4ac2;
            --red: #c92a2a; --gray-100: #f1f3f5; --gray-200: #e9ecef;
            --gray-300: #dee2e6; --gray-600: #868e96; --gray-700: #495057;
            --gray-800: #343a40; --gray-900: #212529; --font: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            font-family: var(--font); background: linear-gradient(135deg, #eef2ff 0%, #f0f4ff 50%, #e8eeff 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem;
        }
        .auth-wrap { width: 100%; max-width: 420px; }
        .auth-logo {
            text-align: center; margin-bottom: 2rem;
        }
        .auth-logo .logo-icon {
            width: 56px; height: 56px; background: var(--blue); border-radius: 16px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 24px; color: #fff; margin-bottom: .75rem; box-shadow: 0 8px 24px rgba(59,91,219,.3);
        }
        .auth-logo h1 { font-size: 22px; font-weight: 700; color: var(--gray-900); }
        .auth-logo p { font-size: 13px; color: var(--gray-600); margin-top: 3px; }
        .auth-card {
            background: #fff; border-radius: 20px; padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,.1), 0 4px 16px rgba(59,91,219,.06);
            border: 1px solid rgba(255,255,255,.8);
        }
        .auth-card h2 { font-size: 18px; font-weight: 700; color: var(--gray-900); margin-bottom: .25rem; }
        .auth-card .subtitle { font-size: 13px; color: var(--gray-600); margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-label {
            display: block; font-size: 12px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .5px; color: var(--gray-700); margin-bottom: 6px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: var(--gray-600); font-size: 14px; pointer-events: none;
        }
        .form-control {
            width: 100%; padding: 10px 12px 10px 36px;
            border: 1px solid var(--gray-300); border-radius: 8px;
            font-family: var(--font); font-size: 14px; color: var(--gray-800);
            outline: none; transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(59,91,219,.12); }
        .is-invalid { border-color: var(--red) !important; }
        .form-error { font-size: 12px; color: var(--red); margin-top: 5px; display: flex; align-items: center; gap: 4px; }
        .remember-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
        .remember-label { display: flex; align-items: center; gap: 7px; font-size: 13px; color: var(--gray-700); cursor: pointer; }
        .remember-label input[type=checkbox] { accent-color: var(--blue); width: 15px; height: 15px; }
        .forgot-link { font-size: 13px; color: var(--blue); text-decoration: none; font-weight: 500; }
        .forgot-link:hover { text-decoration: underline; }
        .btn-submit {
            width: 100%; padding: 11px; background: var(--blue); color: #fff;
            border: none; border-radius: 8px; font-family: var(--font); font-size: 14px;
            font-weight: 700; cursor: pointer; transition: background .15s, transform .1s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-submit:hover { background: var(--blue-dark); }
        .btn-submit:active { transform: scale(.99); }
        .auth-footer { text-align: center; margin-top: 1.25rem; font-size: 13px; color: var(--gray-600); }
        .auth-footer a { color: var(--blue); font-weight: 600; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }
        .alert {
            padding: 10px 14px; border-radius: 8px; font-size: 13px; font-weight: 500;
            margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;
            background: #ffe3e3; color: #7b1c1c; border: 1px solid #ffc9c9;
        }
    </style>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-logo">
        <div class="logo-icon"><i class="fa-solid fa-check"></i></div>
        <h1>To-Do App</h1>
        <p>Stay organised and get things done.</p>
    </div>
    <div class="auth-card">
        <h2>Welcome back!</h2>
        <p class="subtitle">Sign in to your account to continue.</p>

        @if($errors->any())
            <div class="alert"><i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                </div>
                @error('email') <div class="form-error"><i class="fa-solid fa-circle-xmark"></i> {{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="••••••••" required>
                </div>
                @error('password') <div class="form-error"><i class="fa-solid fa-circle-xmark"></i> {{ $message }}</div> @enderror
            </div>
            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                @endif
            </div>
            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-right-to-bracket"></i> Sign In
            </button>
        </form>
        <div class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </div>
    </div>
</div>
</body>
</html>