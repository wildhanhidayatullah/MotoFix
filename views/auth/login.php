<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MotoFix | Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css" />
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .error {
            color: red;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <hgroup>
            <h2>MotoFix</h2>
            <h3>Login</h3>
        </hgroup>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <form action="/login-process" method="POST">
            <label for="username">Username</label>
            <input required type="text" id="username" name="username" placeholder="Masukkan username" />

            <label for="Password">Password</label>
            <input required type="text" id="password" name="password" placeholder="Masukkan password" />

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>