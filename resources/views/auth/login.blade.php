<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Arrester</title>

    <link rel="icon" type="image/png" href="{{ asset('images/Logo_PLN2.png') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b, #0b1b3a);
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .login-card {
            width: 100%;
            max-width: 1100px;
            min-height: 620px;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            background: #ffffff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.28);
        }

        .login-left {
            background: linear-gradient(160deg, #1f2937, #0f172a);
            color: white;
            padding: 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-left .badge {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 999px;
            background: rgba(255,255,255,0.12);
            margin-bottom: 24px;
            font-size: 15px;
            width: fit-content;
        }

        .login-left h1 {
            font-size: 52px;
            line-height: 1.18;
            margin: 0 0 22px 0;
            font-weight: 800;
            max-width: 520px;
        }

        .login-left p {
            font-size: 18px;
            line-height: 1.85;
            color: #d1d5db;
            margin: 0;
            max-width: 500px;
        }

        .login-right {
            padding: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
        }

        .login-form {
            width: 100%;
            max-width: 420px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 22px;
        }

        .login-logo img {
            width: 82px;
            height: auto;
            object-fit: contain;
        }

        .login-form h2 {
            margin: 0 0 8px 0;
            font-size: 56px;
            color: #111827;
            font-weight: 800;
            text-align: left;
        }

        .login-form .subtitle {
            margin: 0 0 34px 0;
            color: #6b7280;
            font-size: 16px;
            line-height: 1.7;
        }

        .field {
            margin-bottom: 22px;
        }

        .field label {
            display: block;
            margin-bottom: 9px;
            font-weight: 700;
            color: #111827;
            font-size: 15px;
        }

        .field input[type="text"],
        .field input[type="password"] {
            width: 100%;
            padding: 16px 18px;
            border: 1px solid #d1d5db;
            border-radius: 16px;
            font-size: 16px;
            background: white;
            outline: none;
            transition: 0.2s ease;
        }

        .field input[type="text"]:focus,
        .field input[type="password"]:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin: 8px 0 28px 0;
            font-size: 15px;
        }

        .remember-box {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #374151;
            cursor: pointer;
        }

        .remember-box input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin: 0;
            accent-color: #2563eb;
        }

        .forgot-link {
            color: #2563eb;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 16px;
            background: #2563eb;
            color: white;
            font-size: 17px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .login-btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .error-box,
        .status-box {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 14px;
            font-size: 14px;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-box {
            background: #dcfce7;
            color: #166534;
        }

        @media (max-width: 980px) {
            .login-card {
                grid-template-columns: 1fr;
                max-width: 650px;
            }

            .login-left,
            .login-right {
                padding: 36px;
            }

            .login-left h1 {
                font-size: 36px;
            }

            .login-form h2 {
                font-size: 42px;
            }
        }

        @media (max-width: 600px) {
            .login-wrapper {
                padding: 16px;
            }

            .login-left,
            .login-right {
                padding: 24px;
            }

            .login-left h1 {
                font-size: 30px;
            }

            .login-left p {
                font-size: 15px;
            }

            .login-form h2 {
                font-size: 36px;
            }

            .remember-row {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-left">
                <span class="badge">Monitoring System</span>
                <h1>Sistem Monitoring Leakage Current Arrester</h1>
                <p>
                    Masuk ke dashboard untuk memantau data pengukuran arrester,
                    histori leakage current, serta pengelolaan data secara real-time.
                </p>
            </div>

            <div class="login-right">
                <div class="login-form">
                    <div class="login-logo">
                        <img src="{{ asset('images/Logo_PLN.png') }}" alt="Logo">
                    </div>

                    <h2>Login</h2>
                    <p class="subtitle">Silakan masuk menggunakan akun Anda</p>

                    @if (session('status'))
                        <div class="status-box">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="error-box">
                            <ul style="margin:0; padding-left:18px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="field">
                            <label for="username">Username</label>
                            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
                        </div>

                        <div class="field">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" required>
                        </div>

                        <div class="remember-row">
                            <label class="remember-box" for="remember_me">
                                <input id="remember_me" type="checkbox" name="remember">
                                <span>Remember me</span>
                            </label>

                            <!-- @if (Route::has('password.request'))
                                <a class="forgot-link" href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif -->
                        </div>

                        <button type="submit" class="login-btn">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>