<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Aplikasi AKSI</title>
    <meta name="description" content="Login to Aplikasi AKSI - Audit dan Kontrol Sistem Informasi">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
    <div class="auth-container">
        <!-- Animated Background Shapes -->
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>

        <!-- Main Auth Card -->
        <div class="auth-card">
            <!-- Left Side - Logo & Description -->
            <div class="auth-left">
                <div class="logo-section">
                    <div class="logo">AKSI</div>
                    <p class="logo-description">
                        Sistem Audit dan Kontrol yang terintegrasi untuk meningkatkan efisiensi dan transparansi dalam
                        pengelolaan temuan audit, rekomendasi, dan tindak lanjut.
                    </p>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="auth-right">
                <!-- Page Title -->
                <h2 style="font-size: 28px; font-weight: 700; color: #088395; margin-bottom: 10px;">Welcome Back</h2>
                <p style="font-size: 14px; color: #666; margin-bottom: 30px;">Please login to access your account</p>

                <!-- Alert Messages -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login.attempt') }}" id="login-form">
                    @csrf

                    <div class="form-group">
                        <label for="user_nik">NIK (Nomor Induk Karyawan)</label>
                        <input type="text" id="user_nik" name="user_nik" placeholder="Enter your NIK"
                            value="{{ old('user_nik') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="user_password">Password</label>
                        <input type="password" id="user_password" name="user_password"
                            placeholder="Enter your password" required>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="submit-btn">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Form Submit Loading State
        const form = document.getElementById('login-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const submitBtn = this.querySelector('.submit-btn');
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>

</html>