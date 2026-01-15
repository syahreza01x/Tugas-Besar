<?php 
$title = 'Profile - AnimeList';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-5">
                    <div class="d-flex justify-content-center mb-3">
                        <img src="<?= baseUrl('assets/img/' . ($user->image ?? '1.png')) ?>" 
                             alt="Profile" class="rounded-circle" 
                             style="width: 150px; height: 150px; object-fit: cover; object-position: top; border: 3px solid #343a40;">
                    </div>
                    <h4 class="mb-1"><?= e($user->name) ?></h4>
                    <p class="text-muted mb-3"><?= e($user->email) ?></p>
                    <span class="badge bg-<?= strtolower($user->gender) == 'pria' ? 'primary' : 'danger' ?> mb-3">
                        <i class="fas fa-<?= strtolower($user->gender) == 'pria' ? 'mars' : 'venus' ?> me-1"></i>
                        <?= e($user->gender) ?>
                    </span>
                    <hr>
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        Member since <?= Helper::formatDate($user->created_at) ?>
                    </small>
                </div>
            </div>

            <!-- Stats -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h3 class="text-primary mb-0"><?= $stats->total_anime ?? 0 ?></h3>
                            <small class="text-muted">Total Anime</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h3 class="text-success mb-0"><?= $stats->completed ?? 0 ?></h3>
                            <small class="text-muted">Completed</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-warning mb-0"><?= $stats->total_reviews ?? 0 ?></h3>
                            <small class="text-muted">Reviews</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-danger mb-0"><?= $stats->total_favorites ?? 0 ?></h3>
                            <small class="text-muted">Favorites</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit Profile</h5>
                </div>
                <div class="card-body">
                    <form action="<?= baseUrl('profile/update') ?>" method="POST">
                        <?= csrfField() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="<?= e($user->name) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" 
                                   value="<?= e($user->email) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="gender-card card p-3 text-center" 
                                         style="cursor: pointer; border: 2px solid <?= $user->gender == 'Pria' ? '#0d6efd' : '#dee2e6' ?> !important; <?= $user->gender == 'Pria' ? 'background-color: rgba(13, 110, 253, 0.05); box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);' : '' ?>"
                                         onclick="selectGender('gender-pria')">
                                        <input class="form-check-input d-none" type="radio" name="gender" 
                                               id="gender-pria" value="Pria" 
                                               <?= $user->gender == 'Pria' ? 'checked' : '' ?>>
                                        <label class="d-block" style="cursor: pointer; margin: 0;" for="gender-pria">
                                            <i class="fas fa-mars fa-2x text-primary mb-2"></i>
                                            <div>Pria</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="gender-card card p-3 text-center" 
                                         style="cursor: pointer; border: 2px solid <?= $user->gender == 'Wanita' ? '#0d6efd' : '#dee2e6' ?> !important; <?= $user->gender == 'Wanita' ? 'background-color: rgba(13, 110, 253, 0.05); box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);' : '' ?>"
                                         onclick="selectGender('gender-wanita')">
                                        <input class="form-check-input d-none" type="radio" name="gender" 
                                               id="gender-wanita" value="Wanita" 
                                               <?= $user->gender == 'Wanita' ? 'checked' : '' ?>>
                                        <label class="d-block" style="cursor: pointer; margin: 0;" for="gender-wanita">
                                            <i class="fas fa-venus fa-2x text-danger mb-2"></i>
                                            <div>Wanita</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">Changing gender will update your profile picture</small>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">Change Password</h6>
                        <p class="text-muted small">Leave blank if you don't want to change password</p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" name="password" id="password" class="form-control">
                                <small class="text-muted">Minimum 6 characters</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
