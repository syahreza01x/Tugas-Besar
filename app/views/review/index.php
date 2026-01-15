<?php 
$title = 'My Reviews - AnimeList';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<style>
/* Spoiler blur effect */
.spoiler-blur {
    filter: blur(5px);
    background-color: #6c757d;
    color: transparent;
    cursor: pointer;
    user-select: none;
    transition: filter 0.3s ease, background-color 0.3s ease, color 0.3s ease;
    padding: 10px;
    border-radius: 8px;
    position: relative;
}

.spoiler-blur::before {
    content: '🔒 Klik untuk melihat spoiler';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: 500;
    filter: none;
    z-index: 10;
    text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    white-space: nowrap;
}

.spoiler-blur.revealed {
    filter: none;
    background-color: transparent;
    color: inherit;
    cursor: default;
    user-select: auto;
}

.spoiler-blur.revealed::before {
    display: none;
}
</style>

<?php $filter = $filter ?? 'mine'; ?>

<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-star text-warning me-2"></i><?= $filter === 'all' ? 'All Reviews' : 'My Reviews' ?></h2>
            <p class="text-muted"><?= $filter === 'all' ? 'Reviews from all users' : 'Your anime reviews and ratings' ?></p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="<?= baseUrl('anime/search') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Write Review
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link <?= $filter === 'mine' ? 'active' : '' ?>" href="<?= baseUrl('reviews?filter=mine') ?>">
                <i class="fas fa-user me-1"></i>My Reviews
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filter === 'all' ? 'active' : '' ?>" href="<?= baseUrl('reviews?filter=all') ?>">
                <i class="fas fa-users me-1"></i>All Reviews
            </a>
        </li>
    </ul>

    <?php if (!empty($reviews)): ?>
        <div class="row">
            <?php foreach ($reviews as $review): ?>
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <?php if ($filter === 'all'): ?>
                                    <!-- User Profile Info -->
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="<?= $review->user_image ? baseUrl('assets/img/' . $review->user_image) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user_name) . '&background=667eea&color=fff' ?>" 
                                             alt="<?= e($review->user_name) ?>" 
                                             class="rounded-circle me-2" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <strong><?= e($review->user_name) ?></strong>
                                            <?php if (isset($currentUserId) && $review->id_user == $currentUserId): ?>
                                                <span class="badge bg-secondary ms-1">You</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="d-flex align-items-start mb-3">
                                        <div>
                                            <h5 class="mb-1"><?= e($review->judul_anime) ?></h5>
                                            <div class="mb-2">
                                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                                    <i class="fas fa-star <?= $i <= $review->rating ? 'text-warning' : 'text-muted' ?>"></i>
                                                <?php endfor; ?>
                                                <span class="ms-2 fw-bold"><?= $review->rating ?>/10</span>
                                            </div>
                                            <?php if ($review->is_spoiler): ?>
                                                <span class="badge bg-danger mb-2">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Contains Spoilers
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if ($review->is_spoiler): ?>
                                        <div class="spoiler-blur mb-2" onclick="this.classList.toggle('revealed')">
                                            <p class="mb-0"><?= nl2br(e($review->review_text)) ?></p>
                                        </div>
                                    <?php else: ?>
                                        <p class="mb-2"><?= nl2br(e($review->review_text)) ?></p>
                                    <?php endif; ?>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= Helper::formatDate($review->created_at, 'd M Y H:i') ?>
                                    </small>
                                </div>
                                <div class="col-md-3 text-md-end">
                                    <?php if (!isset($currentUserId) || $review->id_user == $currentUserId): ?>
                                    <div class="btn-group">
                                        <a href="<?= baseUrl('reviews/edit/' . $review->id) ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?= baseUrl('reviews/delete/' . $review->id) ?>" method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this review?');">
                                            <?= csrfField() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-star fa-4x text-muted mb-3"></i>
                <h5>No reviews yet</h5>
                <p class="text-muted">Share your thoughts about the anime you've watched!</p>
                <a href="<?= baseUrl('anime/search') ?>" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Search Anime to Review
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
