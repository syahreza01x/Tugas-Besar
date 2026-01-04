<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Anime List' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= baseUrl('public/css/style.css') ?>">
    <style>
        .nav-link.active {
            color: #fff !important;
            border-bottom: 2px solid #fff;
        }
        .navbar-profile-img {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px;
            min-height: 32px;
            object-fit: cover !important;
            object-position: top !important;
            border: 2px solid #6c757d !important;
            border-radius: 50% !important;
            background-color: #dee2e6;
        }
    </style>
</head>
<body>
    <?php 
    if (Session::isLoggedIn()): 
        // Get current page for active nav
        $currentUrl = isset($_GET['url']) ? $_GET['url'] : '';
        
        // Get fresh user image from database
        $userModel = new User();
        $currentUser = $userModel->find(Session::get('user_id'));
        $userImage = $currentUser->image ?? '1.png';
    ?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= baseUrl('dashboard') ?>">
                <i class="fas fa-film me-2"></i>AnimeList
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentUrl == 'dashboard' || $currentUrl == '' ? 'active' : '' ?>" href="<?= baseUrl('dashboard') ?>">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentUrl, 'anime') !== false ? 'active' : '' ?>" href="<?= baseUrl('anime/search') ?>">
                            <i class="fas fa-search me-1"></i>Search Anime
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentUrl, 'list') !== false ? 'active' : '' ?>" href="<?= baseUrl('list') ?>">
                            <i class="fas fa-list me-1"></i>My List
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentUrl, 'favorites') !== false ? 'active' : '' ?>" href="<?= baseUrl('favorites') ?>">
                            <i class="fas fa-heart me-1"></i>Favorites
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentUrl, 'reviews') !== false ? 'active' : '' ?>" href="<?= baseUrl('reviews') ?>">
                            <i class="fas fa-star me-1"></i>Reviews
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center <?= strpos($currentUrl, 'profile') !== false ? 'active' : '' ?>" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="<?= baseUrl('assets/img/' . $userImage) ?>" 
                                 alt="Profile" class="me-2 navbar-profile-img">
                            <?= e(Session::get('user_name')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= baseUrl('profile') ?>">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= baseUrl('logout') ?>">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Flash Messages -->
    <div class="container mt-3">
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
                <i class="fas fa-exclamation-circle me-2"></i>
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
    </div>
