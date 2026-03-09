<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Khabar-i-Lal</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 3rem;
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .brand-login {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 2rem;
            letter-spacing: -0.05em;
        }
    </style>
</head>
<body class="auth-body">
    <div class="card login-card">
        <div class="brand-login">Khabar-i-Lal</div>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required autofocus>
                @error('email')
                    <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Sign In
            </button>
        </form>
        <div style="text-align: center; margin-top: 2rem; font-size: 0.9rem; color: #94a3b8;">
            Want to report for us? <a href="{{ route('register') }}" style="color: var(--accent); text-decoration: none; font-weight: 700;">Join our news team</a>
        </div>
    </div>
</body>
</html>

