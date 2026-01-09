<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Multi Event</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #0d1b2a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>
    <div class="login-card p-5">
        <div class="text-center mb-4">
            <span class="fs-1">🎪</span>
            <h4 class="fw-bold mt-2">Admin Login</h4>
            <p class="text-muted">Multi Event Registration</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="form-control form-control-lg" placeholder="admin@example.com">
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Password</label>
                <input type="password" name="password" required class="form-control form-control-lg"
                    placeholder="••••••••">
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label for="remember" class="form-check-label">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">
                Login
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                ← Kembali ke Website
            </a>
        </div>
    </div>
</body>

</html>