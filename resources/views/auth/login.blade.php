

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - RSHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>
<body class="login-body">
    <div class="login-card">
        <div class="login-title">Login RSHP</div>
        <div class="login-subtitle">Silakan login untuk mengakses sistem</div>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <div class="mb-3">
                <label for="username" class="form-label">Username (Email)</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus placeholder="Masukkan email...">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Masukkan password...">
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
        </form>
    </div>
</body>
</html>

