<?php

class AnimeController extends Controller
{
    private $animeListModel;
    private $reviewModel;
    private $favoriteModel;
    private $statusModel;

    public function __construct()
    {
        $this->animeListModel = new AnimeList();
        $this->reviewModel = new Review();
        $this->favoriteModel = new Favorite();
        $this->statusModel = new Status();
    }

    public function search()
    {
        $this->authCheck();

        $statuses = $this->statusModel->getAllStatuses();

        $this->view('anime.search', [
            'statuses' => $statuses
        ]);
    }

    public function addToList()
    {
        $this->authCheck();

        $animeId = $this->input('id_anime');

        if (!$animeId) {
            Session::flash('error', 'Invalid anime ID');
            $this->redirect('anime/search');
        }

        // Fetch anime data from API
        $apiUrl = "https://api.jikan.moe/v4/anime/" . $animeId;
        $content = @file_get_contents($apiUrl);

        if (!$content) {
            Session::flash('error', 'Failed to fetch anime data');
            $this->redirect('anime/search');
        }

        $result = json_decode($content);
        $anime = $result->data;

        // Extract genres
        $genres = [];
        foreach ($anime->genres as $genre) {
            $genres[] = $genre->name;
        }

        // Extract studio
        $studio = !empty($anime->studios) ? $anime->studios[0]->name : 'Unknown';

        $data = [
            'id_anime' => $animeId,
            'id_user' => Session::get('user_id'),
            'judul' => $anime->title,
            'sinopsis' => $anime->synopsis ?? '',
            'studio' => $studio,
            'genre' => implode(', ', $genres),
            'gambar' => $anime->images->jpg->image_url ?? '',
            'status_id' => $this->input('status_id', 1),
            'total_episodes' => $anime->episodes ?? null
        ];

        $result = $this->animeListModel->addToList($data);

        if (isset($result['error'])) {
            Session::flash('error', $result['error']);
            $this->redirect('anime/search');
        } else {
            Session::flash('success', 'Anime added to your list!');
            $this->redirect('list');
        }
    }

    public function toggleFavorite()
    {
        $this->authCheck();

        $animeId = $this->input('id_anime');
        $userId = Session::get('user_id');

        if (!$animeId) {
            $this->json(['error' => 'Invalid anime ID'], 400);
        }

        $existing = $this->favoriteModel->isFavorite($animeId, $userId);

        if ($existing) {
            $this->favoriteModel->removeFavorite($animeId, $userId);
            $this->json(['status' => 'removed', 'message' => 'Removed from favorites']);
        } else {
            // Fetch anime data from API
            $apiUrl = "https://api.jikan.moe/v4/anime/" . $animeId;
            $content = @file_get_contents($apiUrl);

            if (!$content) {
                $this->json(['error' => 'Failed to fetch anime data'], 500);
            }

            $result = json_decode($content);
            $anime = $result->data;

            $data = [
                'id_anime' => $animeId,
                'id_user' => $userId,
                'judul' => $anime->title,
                'gambar' => $anime->images->jpg->image_url ?? ''
            ];

            $this->favoriteModel->addFavorite($data);
            $this->json(['status' => 'added', 'message' => 'Added to favorites']);
        }
    }

    public function getAnimeDetail($id)
    {
        $this->authCheck();

        $apiUrl = "https://api.jikan.moe/v4/anime/" . $id;
        $content = @file_get_contents($apiUrl);

        if (!$content) {
            $this->json(['error' => 'Failed to fetch anime data'], 500);
        }

        $result = json_decode($content);
        $userId = Session::get('user_id');

        // Check if in list
        $inList = $this->animeListModel->findByAnimeAndUser($id, $userId);

        // Check if favorited
        $isFavorite = $this->favoriteModel->isFavorite($id, $userId);

        // Get reviews
        $reviews = $this->reviewModel->getByAnime($id);

        $this->json([
            'anime' => $result->data,
            'inList' => $inList ? true : false,
            'listData' => $inList,
            'isFavorite' => $isFavorite ? true : false,
            'reviews' => $reviews
        ]);
    }
}
