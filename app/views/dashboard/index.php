<?php 
$title = 'Dashboard - AnimeList';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card text-white" style="background: #343a40;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1">Welcome back, <?= e(Session::get('user_name')) ?>! </h2>
                            <p class="mb-0 opacity-75">Track your anime watching progress and discover new shows.</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="<?= baseUrl('anime/search') ?>" class="btn btn-light btn-lg">
                                <i class="fas fa-search me-2"></i>Search Anime
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-primary mb-2">
                        <i class="fas fa-list"></i>
                    </div>
                    <h3 class="mb-0"><?= $stats->total_anime ?? 0 ?></h3>
                    <small class="text-muted">Total Anime</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-success mb-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="mb-0"><?= $stats->completed ?? 0 ?></h3>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-warning mb-2">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <h3 class="mb-0"><?= $stats->watching ?? 0 ?></h3>
                    <small class="text-muted">Watching</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-danger mb-2">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="mb-0"><?= $stats->total_favorites ?? 0 ?></h3>
                    <small class="text-muted">Favorites</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>List Distribution</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($statusCounts)): ?>
                        <?php foreach ($statusCounts as $status): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>
                                    <span class="badge me-2" style="background-color: <?= $status->color ?>">
                                        <?= $status->count ?>
                                    </span>
                                    <?= e($status->name) ?>
                                </span>
                                <div class="progress flex-grow-1 mx-3" style="height: 8px;">
                                    <?php 
                                    $total = array_sum(array_column($statusCounts, 'count'));
                                    $percentage = $total > 0 ? ($status->count / $total) * 100 : 0;
                                    ?>
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?= $percentage ?>%; background-color: <?= $status->color ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">No anime in your list yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-heart me-2 text-danger"></i>Top Favorites</h5>
                    <a href="<?= baseUrl('favorites') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($favorites)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($favorites as $index => $fav): ?>
                                <div class="list-group-item px-0 d-flex align-items-center">
                                    <span class="badge bg-warning text-dark me-3">#<?= $index + 1 ?></span>
                                    <img src="<?= e($fav->gambar) ?>" alt="" class="rounded me-3" 
                                         style="width: 40px; height: 55px; object-fit: cover;">
                                    <span class="text-truncate"><?= e($fav->judul) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No favorites yet. Add some anime to your favorites!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Anime -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Anime in List</h5>
                    <a href="<?= baseUrl('list') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentAnime)): ?>
                        <div class="row">
                            <?php foreach ($recentAnime as $anime): ?>
                                <div class="col-md-2 col-4 mb-3">
                                    <a href="<?= baseUrl('list/' . $anime->id) ?>" class="text-decoration-none">
                                        <div class="card border-0 h-100 anime-card">
                                            <img src="<?= e($anime->gambar) ?>" class="card-img-top" 
                                                 alt="<?= e($anime->judul) ?>"
                                                 style="height: 180px; object-fit: cover;">
                                            <div class="card-body p-2">
                                                <h6 class="card-title text-truncate mb-1 text-dark">
                                                    <?= e($anime->judul) ?>
                                                </h6>
                                                <span class="badge" style="background-color: <?= $anime->status_color ?>; font-size: 10px;">
                                                    <?= e($anime->status_name) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <p class="text-muted">Your anime list is empty. Start searching for anime!</p>
                            <a href="<?= baseUrl('anime/search') ?>" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Search Anime
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
