<?php

class ListController extends Controller
{
    private $animeListModel;
    private $statusModel;

    public function __construct()
    {
        $this->animeListModel = new AnimeList();
        $this->statusModel = new Status();
    }

    public function index()
    {
        $this->authCheck();

        $userId = Session::get('user_id');
        $statusFilter = $this->input('status');

        if ($statusFilter && $statusFilter !== 'all') {
            $animeList = $this->animeListModel->getByUserAndStatus($userId, $statusFilter);
        } else {
            $animeList = $this->animeListModel->getByUser($userId);
        }

        $statuses = $this->statusModel->getAllStatuses();
        $statusCounts = $this->animeListModel->getCountByStatus($userId);

        $this->view('list.index', [
            'animeList' => $animeList,
            'statuses' => $statuses,
            'statusCounts' => $statusCounts,
            'currentFilter' => $statusFilter
        ]);
    }

    public function show($id)
    {
        $this->authCheck();

        $userId = Session::get('user_id');
        $anime = $this->animeListModel->getDetailWithStatus($id, $userId);

        if (!$anime) {
            Session::flash('error', 'Anime not found in your list');
            $this->redirect('list');
        }

        $statuses = $this->statusModel->getAllStatuses();
        
        // Check if anime is in favorites
        $favoriteModel = new Favorite();
        $isFavorite = $favoriteModel->isFavorite($anime->id_anime, $userId);
        
        // Check if user has reviewed this anime
        $reviewModel = new Review();
        $existingReview = $reviewModel->hasReviewed($anime->id_anime, $userId);

        $this->view('list.detail', [
            'anime' => $anime,
            'statuses' => $statuses,
            'isFavorite' => $isFavorite,
            'existingReview' => $existingReview
        ]);
    }

    public function updateStatus()
    {
        $this->authCheck();

        $id = $this->input('id');
        $statusId = $this->input('status_id');
        $userId = Session::get('user_id');

        if (!$id || !$statusId) {
            Session::flash('error', 'Invalid data');
            $this->redirect('list');
        }

        $this->animeListModel->updateStatus($id, $statusId, $userId);

        Session::flash('success', 'Status updated successfully');
        $this->redirect('list');
    }

    public function update($id)
    {
        $this->authCheck();

        $userId = Session::get('user_id');

        $episodesWatched = $this->input('episodes_watched', 0);
        $score = $this->input('score');
        $notes = $this->input('notes', '');
        $statusId = $this->input('status_id');

        // Convert empty score to null for database
        $score = ($score === '' || $score === null) ? null : (int) $score;

        // Update progress
        $this->animeListModel->updateProgress($id, $episodesWatched, $score, $notes, $userId);

        // Update status if provided
        if ($statusId) {
            $this->animeListModel->updateStatus($id, $statusId, $userId);
        }

        Session::flash('success', 'Anime updated successfully');
        $this->redirect('list/' . $id);
    }

    public function delete($id)
    {
        $this->authCheck();

        $userId = Session::get('user_id');

        $this->animeListModel->deleteFromList($id, $userId);

        Session::flash('success', 'Anime removed from your list');
        $this->redirect('list');
    }

    public function toggleFavorite($id)
    {
        $this->authCheck();

        $userId = Session::get('user_id');
        $anime = $this->animeListModel->getDetailWithStatus($id, $userId);

        if (!$anime) {
            Session::flash('error', 'Anime not found');
            $this->redirect('list');
        }

        $favoriteModel = new Favorite();
        $isFavorite = $favoriteModel->isFavorite($anime->id_anime, $userId);

        if ($isFavorite) {
            $favoriteModel->removeFavorite($anime->id_anime, $userId);
            Session::flash('success', 'Removed from favorites');
        } else {
            $favoriteModel->addFavorite([
                'id_anime' => $anime->id_anime,
                'id_user' => $userId,
                'judul' => $anime->judul,
                'gambar' => $anime->gambar
            ]);
            Session::flash('success', 'Added to favorites!');
        }

        $this->redirect('list/' . $id);
    }

    public function storeReview($id)
    {
        $this->authCheck();

        $userId = Session::get('user_id');
        $anime = $this->animeListModel->getDetailWithStatus($id, $userId);

        if (!$anime) {
            Session::flash('error', 'Anime not found');
            $this->redirect('list');
        }

        $data = [
            'id_anime' => $anime->id_anime,
            'id_user' => $userId,
            'judul_anime' => $anime->judul,
            'rating' => $this->input('rating'),
            'review_text' => $this->input('review_text'),
            'is_spoiler' => $this->input('is_spoiler') ? 1 : 0
        ];

        $errors = $this->validate($data, [
            'rating' => 'required|numeric',
            'review_text' => 'required|min:10'
        ]);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            $this->redirect('list/' . $id);
        }

        $reviewModel = new Review();
        $result = $reviewModel->createReview($data);

        if (isset($result['error'])) {
            Session::flash('error', $result['error']);
        } else {
            Session::flash('success', 'Review added successfully!');
        }

        $this->redirect('list/' . $id);
    }
}
