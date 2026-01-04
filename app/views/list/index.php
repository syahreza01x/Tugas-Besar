<?php 
$title = 'My Anime List - AnimeList';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-list me-2"></i>My Anime List</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="<?= baseUrl('anime/search') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Anime
            </a>
        </div>
    </div>

    <!-- Status Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= baseUrl('list') ?>" 
                           class="btn <?= !$currentFilter || $currentFilter == 'all' ? 'btn-primary' : 'btn-outline-primary' ?>">
                            All
                            <span class="badge bg-light text-dark ms-1">
                                <?= array_sum(array_column($statusCounts, 'count')) ?>
                            </span>
                        </a>
                        <?php foreach ($statusCounts as $status): ?>
                            <a href="<?= baseUrl('list?status=' . $status->name) ?>" 
                               class="btn <?= $currentFilter == $status->name ? 'btn-primary' : 'btn-outline-secondary' ?>"
                               style="<?= $currentFilter == $status->name ? 'background-color: ' . $status->color . '; border-color: ' . $status->color : '' ?>">
                                <?= e($status->name) ?>
                                <span class="badge bg-light text-dark ms-1"><?= $status->count ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Anime List -->
    <?php if (!empty($animeList)): ?>
        <div class="row">
            <?php foreach ($animeList as $anime): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 anime-card">
                        <div class="position-relative">
                            <img src="<?= e($anime->gambar) ?>" class="card-img-top" 
                                 alt="<?= e($anime->judul) ?>" style="height: 280px; object-fit: cover;">
                            <span class="badge position-absolute top-0 end-0 m-2" 
                                  style="background-color: <?= $anime->status_color ?>">
                                <?= e($anime->status_name) ?>
                            </span>
                            <?php if ($anime->score): ?>
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                                    <i class="fas fa-star"></i> <?= $anime->score ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title text-truncate" title="<?= e($anime->judul) ?>">
                                <?= e($anime->judul) ?>
                            </h6>
                            <small class="text-muted d-block mb-2">
                                <i class="fas fa-tv me-1"></i><?= e($anime->studio) ?>
                            </small>
                            <?php if ($anime->episodes_watched || $anime->total_episodes): ?>
                                <div class="progress mb-2" style="height: 5px;">
                                    <?php 
                                    $progress = $anime->total_episodes 
                                        ? ($anime->episodes_watched / $anime->total_episodes) * 100 
                                        : 0;
                                    ?>
                                    <div class="progress-bar" style="width: <?= $progress ?>%; background-color: <?= $anime->status_color ?>"></div>
                                </div>
                                <small class="text-muted">
                                    <?= $anime->episodes_watched ?>/<?= $anime->total_episodes ?? '?' ?> episodes
                                </small>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex gap-2">
                                <a href="<?= baseUrl('list/' . $anime->id) ?>" class="btn btn-sm btn-outline-primary flex-grow-1">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                        data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <?php foreach ($statuses as $status): ?>
                                        <li>
                                            <form action="<?= baseUrl('list/update-status') ?>" method="POST" class="d-inline">
                                                <?= csrfField() ?>
                                                <input type="hidden" name="id" value="<?= $anime->id ?>">
                                                <input type="hidden" name="status_id" value="<?= $status->id ?>">
                                                <button type="submit" class="dropdown-item">
                                                    <span class="badge me-2" style="background-color: <?= $status->color ?>">•</span>
                                                    <?= e($status->name) ?>
                                                </button>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="<?= baseUrl('list/delete/' . $anime->id) ?>" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to remove this anime from your list?');">
                                            <?= csrfField() ?>
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash me-2"></i>Remove
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5>No anime found</h5>
                <p class="text-muted">
                    <?php if ($currentFilter && $currentFilter != 'all'): ?>
                        No anime with status "<?= e($currentFilter) ?>".
                    <?php else: ?>
                        Your anime list is empty. Start adding anime!
                    <?php endif; ?>
                </p>
                <a href="<?= baseUrl('anime/search') ?>" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Search Anime
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.anime-card {
    transition: transform 0.3s, box-shadow 0.3s;
}
.anime-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
