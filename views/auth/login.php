<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MotoFix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease;
        }
        .login-card .alert {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            text-align: left;
            line-height: 1.2;
        }
        .login-card .alert .btn-close {
            padding: 0.6rem 1rem;
        }
        .login-card:hover {
            transform: translateY(-5px);
        }
        .brand-title {
            font-weight: 800;
            color: #224abe;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .brand-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }
        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 12px 15px;
            font-size: 0.95rem;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        .btn-login {
            background: #4e73df;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #2e59d9;
            transform: scale(1.02);
        }
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-left: none;
            cursor: pointer;
            color: #6c757d;
        }
        .password-field {
            border-right: none;
        }
    </style>
</head>
<body>
    <div class="login-card text-center">
        <div class="mb-4">
            <i class="fas fa-tools fa-3x text-primary mb-3"></i>
            <h2 class="brand-title">MOTOFIX</h2>
            <p class="brand-subtitle">Sistem Manajemen Bengkel Profesional</p>
        </div>
        <?php flash(); ?>
        <form action="/login-process" method="POST" autocomplete="off">
            <?php csrfToken(); ?>
            <div class="form-group mb-3 text-start">
                <label for="username" class="form-label fw-bold small text-muted">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user"></i></span>
                    <input login-process type="text" class="form-control border-start-0" id="username" name="username" placeholder="Masukkan username" autofocusautocomplete="off" />
                </div>
            </div>
            <div class="form-group mb-4 text-start">
                <label for="password" class="form-label fw-bold small text-muted">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock"></i></span>
                    <input required type="password" class="form-control password-field border-start-0" id="password" name="password" placeholder="Masukkan password" autocomplete="new-password">
                    <span class="input-group-text" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-login w-100">
                MASUK <i class="fas fa-sign-in-alt ms-2"></i>
            </button>
        </form>
        <div class="mt-4 text-muted small">
            &copy; <?= date('Y'); ?> MotoFix v1.0
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>