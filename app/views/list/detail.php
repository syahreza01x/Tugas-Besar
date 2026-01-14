<?php 
$title = $anime->judul . ' - AnimeList';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="<?= baseUrl('list') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Anime Info -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <img src="<?= e($anime->gambar) ?>" class="card-img-top" alt="<?= e($anime->judul) ?>">
                <div class="card-body">
                    <span class="badge mb-2" style="background-color: <?= $anime->status_color ?>">
                        <?= e($anime->status_name) ?>
                    </span>
                    <h4 class="card-title"><?= e($anime->judul) ?></h4>
                    <p class="text-muted mb-2">
                        <i class="fas fa-tv me-2"></i><?= e($anime->studio) ?>
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-tags me-2"></i><?= e($anime->genre) ?>
                    </p>
                    <?php if ($anime->score): ?>
                        <p class="text-warning mb-0">
                            <i class="fas fa-star me-2"></i>Your Score: <?= $anime->score ?>/10
                        </p>
                    <?php endif; ?>
                    
                    <!-- Toggle Favorite Button -->
                    <form action="<?= baseUrl('list/toggle-favorite/' . $anime->id) ?>" method="POST" class="mt-3">
                        <?= csrfField() ?>
                        <?php if ($isFavorite): ?>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-heart me-2"></i>Remove from Favorites
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="far fa-heart me-2"></i>Add to Favorites
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Update Progress</h5>
                </div>
                <div class="card-body">
                    <form action="<?= baseUrl('list/update/' . $anime->id) ?>" method="POST">
                        <?= csrfField() ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status_id" class="form-label">Status</label>
                                <select name="status_id" id="status_id" class="form-select">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?= $status->id ?>" 
                                                <?= $anime->status_id == $status->id ? 'selected' : '' ?>>
                                            <?= e($status->name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="score" class="form-label">Your Score (1-10)</label>
                                <input type="number" name="score" id="score" class="form-control" 
                                       min="1" max="10" value="<?= $anime->score ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="episodes_watched" class="form-label">Episodes Watched</label>
                                <input type="number" name="episodes_watched" id="episodes_watched" 
                                       class="form-control" min="0" 
                                       value="<?= $anime->episodes_watched ?>"
                                       max="<?= $anime->total_episodes ?? 9999 ?>">
                                <?php if ($anime->total_episodes): ?>
                                    <small class="text-muted">of <?= $anime->total_episodes ?> episodes</small>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Progress</label>
                                <?php 
                                $progress = $anime->total_episodes 
                                    ? ($anime->episodes_watched / $anime->total_episodes) * 100 
                                    : 0;
                                ?>
                                <div class="progress" style="height: 38px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?= $progress ?>%; background-color: <?= $anime->status_color ?>">
                                        <?= round($progress) ?>%
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" 
                                      placeholder="Add your personal notes about this anime..."><?= e($anime->notes ?? '') ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <form action="<?= baseUrl('list/delete/' . $anime->id) ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to remove this anime from your list?');">
                                <?= csrfField() ?>
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash me-2"></i>Remove from List
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Synopsis -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Synopsis</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?= nl2br(e($anime->sinopsis)) ?></p>
                </div>
            </div>

            <!-- Review Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-pen me-2"></i>Write a Review</h5>
                </div>
                <div class="card-body">
                    <?php if ($existingReview): ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            You have already reviewed this anime. 
                            <a href="<?= baseUrl('reviews') ?>" class="alert-link">View your reviews</a>
                        </div>
                    <?php else: ?>
                        <form action="<?= baseUrl('list/review/' . $anime->id) ?>" method="POST">
                            <?= csrfField() ?>
                            
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating (1-10)</label>
                                <select name="rating" id="rating" class="form-select" required>
                                    <option value="">Select rating...</option>
                                    <?php for ($i = 10; $i >= 1; $i--): ?>
                                        <option value="<?= $i ?>"><?= $i ?> - <?= 
                                            $i >= 9 ? 'Masterpiece' : 
                                            ($i >= 7 ? 'Great' : 
                                            ($i >= 5 ? 'Average' : 
                                            ($i >= 3 ? 'Bad' : 'Terrible'))) 
                                        ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="review_text" class="form-label">Your Review</label>
                                <textarea name="review_text" id="review_text" class="form-control" rows="5" 
                                          placeholder="Share your thoughts about this anime... (minimum 10 characters)" 
                                          required minlength="10"></textarea>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_spoiler" id="is_spoiler" class="form-check-input" value="1">
                                <label for="is_spoiler" class="form-check-label">This review contains spoilers</label>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Submit Review
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
