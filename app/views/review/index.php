<?php 
$title = 'My Reviews - AnimeList';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-star text-warning me-2"></i>My Reviews</h2>
            <p class="text-muted">Your anime reviews and ratings</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="<?= baseUrl('anime/search') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Write Review
            </a>
        </div>
    </div>

    <?php if (!empty($reviews)): ?>
        <div class="row">
            <?php foreach ($reviews as $review): ?>
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
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
                                    <p class="mb-2"><?= nl2br(e($review->review_text)) ?></p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= Helper::formatDate($review->created_at, 'd M Y H:i') ?>
                                    </small>
                                </div>
                                <div class="col-md-3 text-md-end">
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
