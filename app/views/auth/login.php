<?php $title = 'Login - AnimeList'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            background: #343a40;
            color: white;
            border-radius: 8px 8px 0 0;
            padding: 25px;
            text-align: center;
        }
        .btn-login {
            background: #343a40;
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            background: #23272b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <!-- Flash Messages -->
                <?php if (Session::hasFlash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= Session::flash('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (Session::hasFlash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= Session::flash('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (Session::hasFlash('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach (Session::flash('errors') as $field => $errors): ?>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= e($error) ?></li>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-film fa-3x mb-3"></i>
                        <h2 class="mb-0">AnimeList</h2>
                        <p class="mb-0">Track your anime journey</p>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Login</h4>
                        <form action="<?= baseUrl('login') ?>" method="POST">
                            <?= csrfField() ?>
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= old('email') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                        <hr>
                        <p class="text-center mb-0">
                            Don't have an account? 
                            <a href="<?= baseUrl('register') ?>" class="text-decoration-none fw-bold">Register</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
