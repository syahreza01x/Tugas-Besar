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

        $this->view('list.detail', [
            'anime' => $anime,
            'statuses' => $statuses
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
}
