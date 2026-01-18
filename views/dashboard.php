<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title><?= $data['title']; ?></title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }
        .card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Selamat Datang di MotoFix</h1>
        <p>Halo, <strong><?= $data['user']; ?></strong></p>
        <p>Sistem manajemen bengkel profesional Anda siap digunakan.</p>
    </div>
</body>
</html>