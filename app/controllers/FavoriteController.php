<?php

class FavoriteController extends Controller
{
    private $favoriteModel;

    public function __construct()
    {
        $this->favoriteModel = new Favorite();
    }

    public function index()
    {
        $this->authCheck();

        $userId = Session::get('user_id');
        $favorites = $this->favoriteModel->getByUser($userId);

        $this->view('favorite.index', [
            'favorites' => $favorites
        ]);
    }

    public function updateRanking()
    {
        $this->authCheck();

        $userId = Session::get('user_id');
        $rankings = $this->input('rankings');

        if ($rankings && is_array($rankings)) {
            foreach ($rankings as $id => $ranking) {
                $this->favoriteModel->updateRanking($id, $ranking, $userId);
            }
        }

        Session::flash('success', 'Rankings updated successfully');
        $this->redirect('favorites');
    }

    public function delete($id)
    {
        $this->authCheck();

        $userId = Session::get('user_id');
        $favorite = $this->favoriteModel->find($id);

        if ($favorite && $favorite->id_user == $userId) {
            $this->favoriteModel->removeFavorite($favorite->id_anime, $userId);
            Session::flash('success', 'Removed from favorites');
        }

        $this->redirect('favorites');
    }
}
