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
                <div class="password-input-group">
                    <input type="password" name="password" class="form-control" required>
                    <button type="button" class="password-toggle-btn" onclick="togglePassword(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.79 0 8.601 3.049 9.964 6.678.045.122.045.28 0 .402-1.364 3.629-5.174 6.678-9.964 6.678-4.79 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Sign In
            </button>
        </form>
        <div style="text-align: center; margin-top: 2rem; font-size: 0.9rem; color: #94a3b8;">
            Want to report for us? <a href="{{ route('register') }}" style="color: var(--accent); text-decoration: none; font-weight: 700;">Join our news team</a>
        </div>
    </div>

    <script>
        function togglePassword(btn) {
            const input = btn.closest('.password-input-group').querySelector('input');
            const showIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.79 0 8.601 3.049 9.964 6.678.045.122.045.28 0 .402-1.364 3.629-5.174 6.678-9.964 6.678-4.79 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`;
            const hideIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>`;

            if (input.type === "password") {
                input.type = "text";
                btn.innerHTML = hideIcon;
            } else {
                input.type = "password";
                btn.innerHTML = showIcon;
            }
        }
    </script>
</body>
</html>

