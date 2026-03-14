<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporter Registration - Khabar-i-Lal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root {
            --brand-red: #e11d48;
            --brand-black: #0f172a;
            --bg-dark: #f8fafc;
            --border-light: #e2e8f0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 2rem;
        }

        .auth-container {
            width: 100%;
            max-width: 600px;
        }

        .logo-area {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-text {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -1.5px;
            color: var(--brand-black);
            margin: 0;
        }

        .logo-text span {
            color: var(--brand-red);
        }

        .auth-card {
            background: #fff;
            padding: 2.5rem;
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--border-light);
        }

        h2 {
            font-weight: 800;
            margin-top: 0;
            margin-bottom: 0.5rem;
            color: var(--brand-black);
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 2.5rem;
            font-size: 0.95rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 0.75rem;
            border: 1.5px solid var(--border-light);
            background: #f8fafc;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--brand-red);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.1);
        }

        .btn-submit {
            width: 100%;
            background: var(--brand-red);
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s, background 0.2s;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: #be123c;
            transform: translateY(-2px);
        }

        .error-msg {
            color: var(--brand-red);
            font-size: 0.8rem;
            margin-top: 0.4rem;
            font-weight: 500;
        }

        .footer-link {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #64748b;
        }

        .footer-link a {
            color: var(--brand-red);
            text-decoration: none;
            font-weight: 700;
        }

        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="logo-area">
            <h1 class="logo-text">Khabar-i-<span>Lal</span></h1>
        </div>

        <div class="auth-card">
            <h2>Join Our News Team</h2>
            <p class="subtitle">Submit your details to become a verified reporter.</p>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. John Doe">
                        @error('name') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="john@example.com">
                        @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="password-input-group">
                            <input type="password" name="password" class="form-control" required placeholder="Minimum 8 characters">
                            <button type="button" class="password-toggle-btn" onclick="togglePassword(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.79 0 8.601 3.049 9.964 6.678.045.122.045.28 0 .402-1.364 3.629-5.174 6.678-9.964 6.678-4.79 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </button>
                        </div>
                        @error('password') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <div class="password-input-group">
                            <input type="password" name="password_confirmation" class="form-control" required placeholder="Repeat password">
                            <button type="button" class="password-toggle-btn" onclick="togglePassword(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.79 0 8.601 3.049 9.964 6.678.045.122.045.28 0 .402-1.364 3.629-5.174 6.678-9.964 6.678-4.79 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Beat / Focus Area</label>
                        <input type="text" name="beat" class="form-control" value="{{ old('beat') }}" placeholder="e.g. Political Crimes, Local Sports">
                        @error('beat') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Journalist Biography</label>
                        <textarea name="bio" class="form-control" rows="3" placeholder="A brief professional summary...">{{ old('bio') }}</textarea>
                        @error('bio') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button type="submit" class="btn-submit">Register as Reporter</button>
            </form>

            <div class="footer-link">
                Already have an account? <a href="{{ route('login') }}">Log In</a>
            </div>
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
