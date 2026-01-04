<?php 
$title = 'Search Anime - AnimeList';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="container py-4">
    <!-- Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-3"><i class="fas fa-search me-2"></i>Search Anime</h4>
                    <div class="row">
                        <div class="col-md-10 mb-2">
                            <input type="text" class="form-control form-control-lg" id="search-input" 
                                   placeholder="Enter anime title... (e.g., Naruto, One Piece, Attack on Titan)">
                        </div>
                        <div class="col-md-2 mb-2">
                            <button class="btn btn-primary btn-lg w-100" id="search-button">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Info -->
    <div class="row mb-3">
        <div class="col-12">
            <h5 id="pencarian" class="text-muted"></h5>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loading" class="text-center py-5 d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Searching anime...</p>
    </div>

    <!-- Results -->
    <div class="row" id="root">
        <!-- Anime cards will be loaded here -->
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="animeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Anime Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Detail will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Add to List Modal -->
<div class="modal fade" id="addListModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= baseUrl('anime/add-to-list') ?>" method="POST">
                <?= csrfField() ?>
                <input type="hidden" name="id_anime" id="add-anime-id">
                <div class="modal-header">
                    <h5 class="modal-title">Add to My List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="add-anime-title" class="fw-bold mb-3"></p>
                    <div class="mb-3">
                        <label for="status_id" class="form-label">Status</label>
                        <select name="status_id" id="status_id" class="form-select">
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= $status->id ?>"><?= e($status->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add to List
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= baseUrl('reviews/store') ?>" method="POST">
                <?= csrfField() ?>
                <input type="hidden" name="id_anime" id="review-anime-id">
                <input type="hidden" name="judul_anime" id="review-anime-title-input">
                <div class="modal-header">
                    <h5 class="modal-title">Write a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="review-anime-title" class="fw-bold mb-3"></p>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (1-10)</label>
                        <input type="number" name="rating" id="rating" class="form-control" 
                               min="1" max="10" required>
                    </div>
                    <div class="mb-3">
                        <label for="review_text" class="form-label">Your Review</label>
                        <textarea name="review_text" id="review_text" class="form-control" 
                                  rows="5" required placeholder="Write your thoughts about this anime..."></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_spoiler" id="is_spoiler" class="form-check-input" value="1">
                        <label for="is_spoiler" class="form-check-label">Contains spoilers</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.anime-card {
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
}
.anime-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.anime-poster {
    position: relative;
    overflow: hidden;
}
.anime-poster img {
    transition: transform 0.3s;
}
.anime-card:hover .anime-poster img {
    transform: scale(1.05);
}
.anime-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    padding: 15px;
    color: white;
}
</style>

<script>
const BASE_URL = '<?= baseUrl('') ?>';

// Search functionality
document.getElementById('search-button').addEventListener('click', function() {
    const query = document.getElementById('search-input').value.trim();
    if (query === '') {
        alert('Please enter anime title to search');
        return;
    }
    searchAnime(query);
});

document.getElementById('search-input').addEventListener('keydown', function(e) {
    if (e.keyCode === 13) {
        const query = this.value.trim();
        if (query === '') {
            alert('Please enter anime title to search');
            return;
        }
        searchAnime(query);
    }
});

async function searchAnime(query) {
    document.getElementById('pencarian').textContent = 'Search results for: ' + query;
    document.getElementById('loading').classList.remove('d-none');
    document.getElementById('root').innerHTML = '';

    try {
        const response = await fetch(`https://api.jikan.moe/v4/anime?q=${encodeURIComponent(query)}&sfw`);
        const json = await response.json();
        const data = json.data;

        document.getElementById('loading').classList.add('d-none');

        if (data.length === 0) {
            document.getElementById('root').innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <p class="text-muted">No anime found for "${query}"</p>
                </div>
            `;
            return;
        }

        data.forEach((anime) => {
            const genres = anime.genres.map(g => g.name).join(', ') || 'N/A';
            const studios = anime.studios.map(s => s.name).join(', ') || 'N/A';
            
            document.getElementById('root').innerHTML += `
                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 anime-card" data-id="${anime.mal_id}">
                        <div class="anime-poster">
                            <img src="${anime.images.jpg.image_url}" class="card-img-top" 
                                 alt="${anime.title}" style="height: 250px; object-fit: cover;">
                            <div class="anime-overlay">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span>${anime.score || 'N/A'}</span>
                                </div>
                                <small>${anime.type || 'N/A'} • ${anime.episodes || '?'} eps</small>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <h6 class="card-title text-truncate mb-1" title="${anime.title}">
                                ${anime.title}
                            </h6>
                            <small class="text-muted">${anime.season ? anime.season.charAt(0).toUpperCase() + anime.season.slice(1) : ''} ${anime.year || ''}</small>
                        </div>
                        <div class="card-footer bg-white border-0 p-2 pt-0">
                            <button class="btn btn-sm btn-primary w-100 btn-detail" data-id="${anime.mal_id}">
                                <i class="fas fa-info-circle me-1"></i>Detail
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
    } catch (error) {
        document.getElementById('loading').classList.add('d-none');
        document.getElementById('root').innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                <p class="text-danger">Failed to fetch anime data. Please try again.</p>
            </div>
        `;
    }
}

// Show detail modal
document.getElementById('root').addEventListener('click', async function(e) {
    const detailBtn = e.target.closest('.btn-detail');
    if (detailBtn) {
        const animeId = detailBtn.dataset.id;
        await showAnimeDetail(animeId);
    }
});

async function showAnimeDetail(animeId) {
    const modal = new bootstrap.Modal(document.getElementById('animeModal'));
    document.getElementById('modal-body').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    modal.show();

    try {
        const response = await fetch(`https://api.jikan.moe/v4/anime/${animeId}`);
        const json = await response.json();
        const anime = json.data;

        const genres = anime.genres.map(g => g.name).join(', ') || 'N/A';
        const studios = anime.studios.map(s => s.name).join(', ') || 'N/A';
        const trailerImage = anime.trailer?.images?.image_url || '';
        const trailerUrl = anime.trailer?.url || '#';

        document.getElementById('modal-body').innerHTML = `
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    <img src="${anime.images.jpg.large_image_url || anime.images.jpg.image_url}" 
                         class="img-fluid rounded shadow" style="max-height: 400px;" alt="${anime.title}">
                    <div class="mt-3">
                        <h4 class="text-warning mb-0">
                            <i class="fas fa-star"></i> ${anime.score || 'N/A'}
                        </h4>
                        <small class="text-muted">${anime.scored_by?.toLocaleString() || 0} users</small>
                    </div>
                    <p class="mt-2 mb-0"><small class="text-muted">${anime.rating || 'N/A'}</small></p>
                </div>
                <div class="col-md-6">
                    <h3 class="mb-1">${anime.title}</h3>
                    <p class="text-muted mb-3">${anime.title_english || ''} ${anime.title_japanese ? '/ ' + anime.title_japanese : ''}</p>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <p class="mb-1"><strong>Type:</strong> ${anime.type || 'N/A'}</p>
                            <p class="mb-1"><strong>Episodes:</strong> ${anime.episodes || '?'}</p>
                            <p class="mb-1"><strong>Status:</strong> ${anime.status || 'N/A'}</p>
                            <p class="mb-1"><strong>Duration:</strong> ${anime.duration || 'N/A'}</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Year:</strong> ${anime.year || 'N/A'}</p>
                            <p class="mb-1"><strong>Season:</strong> ${anime.season ? anime.season.charAt(0).toUpperCase() + anime.season.slice(1) : 'N/A'}</p>
                            <p class="mb-1"><strong>Source:</strong> ${anime.source || 'N/A'}</p>
                            <p class="mb-1"><strong>Rank:</strong> #${anime.rank || 'N/A'}</p>
                        </div>
                    </div>
                    
                    <p class="mb-1"><strong>Genres:</strong> ${genres}</p>
                    <p class="mb-3"><strong>Studios:</strong> ${studios}</p>
                    
                    <div class="synopsis-container" style="max-height: 200px; overflow-y: auto;">
                        <p class="mb-0">${anime.synopsis || 'No synopsis available.'}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    ${trailerImage ? `
                        <a href="${trailerUrl}" target="_blank" class="d-block mb-3">
                            <div class="position-relative">
                                <img src="${trailerImage}" class="img-fluid rounded" alt="Trailer">
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <i class="fas fa-play-circle fa-3x text-white"></i>
                                </div>
                            </div>
                            <small class="text-muted">Watch Trailer</small>
                        </a>
                    ` : ''}
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-add-list" 
                                data-id="${anime.mal_id}" 
                                data-title="${anime.title}">
                            <i class="fas fa-plus me-2"></i>Add to List
                        </button>
                        <button class="btn btn-outline-danger btn-favorite" data-id="${anime.mal_id}">
                            <i class="fas fa-heart me-2"></i>Favorite
                        </button>
                        <button class="btn btn-outline-warning btn-review" 
                                data-id="${anime.mal_id}" 
                                data-title="${anime.title}">
                            <i class="fas fa-star me-2"></i>Write Review
                        </button>
                    </div>
                </div>
            </div>
        `;
    } catch (error) {
        document.getElementById('modal-body').innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p>Failed to load anime details</p>
            </div>
        `;
    }
}

// Add to list button
document.getElementById('modal-body').addEventListener('click', function(e) {
    if (e.target.closest('.btn-add-list')) {
        const btn = e.target.closest('.btn-add-list');
        document.getElementById('add-anime-id').value = btn.dataset.id;
        document.getElementById('add-anime-title').textContent = btn.dataset.title;
        
        const addModal = new bootstrap.Modal(document.getElementById('addListModal'));
        addModal.show();
    }
    
    if (e.target.closest('.btn-review')) {
        const btn = e.target.closest('.btn-review');
        document.getElementById('review-anime-id').value = btn.dataset.id;
        document.getElementById('review-anime-title').textContent = btn.dataset.title;
        document.getElementById('review-anime-title-input').value = btn.dataset.title;
        
        const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
        reviewModal.show();
    }
    
    if (e.target.closest('.btn-favorite')) {
        const btn = e.target.closest('.btn-favorite');
        toggleFavorite(btn.dataset.id);
    }
});

async function toggleFavorite(animeId) {
    try {
        const formData = new FormData();
        formData.append('id_anime', animeId);
        
        const response = await fetch(BASE_URL + 'anime/toggle-favorite', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        alert(result.message);
    } catch (error) {
        alert('Failed to toggle favorite');
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
