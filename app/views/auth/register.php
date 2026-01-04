<?php $title = 'Register - AnimeList'; ?>
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
            padding: 30px 0;
        }
        .register-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .register-header {
            background: #343a40;
            color: white;
            border-radius: 8px 8px 0 0;
            padding: 25px;
            text-align: center;
        }
        .btn-register {
            background: #343a40;
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-register:hover {
            background: #23272b;
        }
        .gender-option {
            cursor: pointer;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: all 0.2s;
        }
        .gender-option:hover {
            border-color: #343a40;
        }
        .gender-option.selected {
            border-color: #343a40;
            background-color: #f8f9fa;
        }
        .gender-option input {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Flash Messages -->
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

                <div class="register-card">
                    <div class="register-header">
                        <i class="fas fa-film fa-3x mb-3"></i>
                        <h2 class="mb-0">AnimeList</h2>
                        <p class="mb-0">Join the community</p>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Create Account</h4>
                        <form action="<?= baseUrl('register') ?>" method="POST">
                            <?= csrfField() ?>
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Full Name
                                </label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= old('name') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= old('email') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-venus-mars me-2"></i>Gender
                                </label>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="gender-option w-100 <?= old('gender') == 'Pria' ? 'selected' : '' ?>">
                                            <input type="radio" name="gender" value="Pria" <?= old('gender') == 'Pria' ? 'checked' : '' ?> required>
                                            <i class="fas fa-mars fa-2x text-primary mb-2"></i>
                                            <div>Pria</div>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <label class="gender-option w-100 <?= old('gender') == 'Wanita' ? 'selected' : '' ?>">
                                            <input type="radio" name="gender" value="Wanita" <?= old('gender') == 'Wanita' ? 'checked' : '' ?>>
                                            <i class="fas fa-venus fa-2x text-danger mb-2"></i>
                                            <div>Wanita</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="text-muted">Minimum 6 characters</small>
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Confirm Password
                                </label>
                                <input type="password" class="form-control" id="password_confirmation" 
                                       name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-register w-100 mb-3">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </button>
                        </form>
                        <hr>
                        <p class="text-center mb-0">
                            Already have an account? 
                            <a href="<?= baseUrl('login') ?>" class="text-decoration-none fw-bold">Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.gender-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.gender-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    </script>
</body>
</html>
