<?php 
$title = 'Edit Review - AnimeList';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Back Button -->
            <a href="<?= baseUrl('reviews') ?>" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Reviews
            </a>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Review</h5>
                </div>
                <div class="card-body">
                    <h4 class="mb-4"><?= e($review->judul_anime) ?></h4>

                    <form action="<?= baseUrl('reviews/update/' . $review->id) ?>" method="POST">
                        <?= csrfField() ?>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating (1-10)</label>
                            <input type="number" name="rating" id="rating" class="form-control" 
                                   min="1" max="10" value="<?= $review->rating ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="review_text" class="form-label">Your Review</label>
                            <textarea name="review_text" id="review_text" class="form-control" 
                                      rows="6" required><?= e($review->review_text) ?></textarea>
                        </div>

                        <div class="form-check mb-4">
                            <input type="checkbox" name="is_spoiler" id="is_spoiler" 
                                   class="form-check-input" value="1" 
                                   <?= $review->is_spoiler ? 'checked' : '' ?>>
                            <label for="is_spoiler" class="form-check-label">Contains spoilers</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Review
                            </button>
                            <a href="<?= baseUrl('reviews') ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
