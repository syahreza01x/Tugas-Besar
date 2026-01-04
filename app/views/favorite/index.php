<?php 
$title = 'My Favorites - AnimeList';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-heart text-danger me-2"></i>My Favorites</h2>
            <p class="text-muted">Your top favorite anime collection</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="<?= baseUrl('anime/search') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Favorites
            </a>
        </div>
    </div>

    <?php if (!empty($favorites)): ?>
        <div class="row">
            <?php foreach ($favorites as $index => $fav): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 favorite-card">
                        <div class="position-relative">
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 fs-6">
                                #<?= $fav->ranking ?? ($index + 1) ?>
                            </span>
                            <img src="<?= e($fav->gambar) ?>" class="card-img-top" 
                                 alt="<?= e($fav->judul) ?>" style="height: 300px; object-fit: cover;">
                            <div class="card-img-overlay d-flex align-items-end p-0">
                                <div class="w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                    <h6 class="text-white mb-0 text-truncate"><?= e($fav->judul) ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 p-2">
                            <form action="<?= baseUrl('favorites/delete/' . $fav->id) ?>" method="POST"
                                  onsubmit="return confirm('Remove from favorites?');">
                                <?= csrfField() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="fas fa-heart-broken me-2"></i>Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-heart fa-4x text-muted mb-3"></i>
                <h5>No favorites yet</h5>
                <p class="text-muted">Start adding anime to your favorites from the search page!</p>
                <a href="<?= baseUrl('anime/search') ?>" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Search Anime
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.favorite-card {
    transition: transform 0.3s, box-shadow 0.3s;
}
.favorite-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
