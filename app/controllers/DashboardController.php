<?php

class DashboardController extends Controller
{
    private $animeListModel;
    private $userModel;
    private $reviewModel;
    private $favoriteModel;

    public function __construct()
    {
        $this->animeListModel = new AnimeList();
        $this->userModel = new User();
        $this->reviewModel = new Review();
        $this->favoriteModel = new Favorite();
    }

    public function index()
    {
        $this->authCheck();

        $userId = Session::get('user_id');

        // Get stats
        $stats = $this->userModel->getStats($userId);

        // Get count by status
        $statusCounts = $this->animeListModel->getCountByStatus($userId);

        // Get recent anime in list
        $recentAnime = $this->animeListModel->getByUser($userId);
        $recentAnime = array_slice($recentAnime, 0, 6);

        // Get top favorites
        $favorites = $this->favoriteModel->getTopFavorites($userId, 5);

        $this->view('dashboard.index', [
            'stats' => $stats,
            'statusCounts' => $statusCounts,
            'recentAnime' => $recentAnime,
            'favorites' => $favorites
        ]);
    }
}
