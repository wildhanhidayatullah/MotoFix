<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $data['title'] ?? 'MotoFix'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/"><strong>MotoFix</strong></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/inventory">Inventaris</a></li>
                    <li class="nav-item"><a class="nav-link" href="/customers">Pelanggan</a></li>
                    <li class="nav-item"><a class="nav-link" href="/transactions">Transaksi</a></li>
                </ul>
                <span class="navbar-text text-white me-3">
                    Halo, <?= $_SESSION['username'] ?? 'User'; ?>!
                </span>
                <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container">