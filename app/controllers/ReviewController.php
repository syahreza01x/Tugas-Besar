<?php

class ReviewController extends Controller
{
    private $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new Review();
    }

    public function index()
    {
        $this->authCheck();

        $userId = Session::get('user_id');
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'mine';
        
        if ($filter === 'all') {
            $reviews = $this->reviewModel->getAllReviews();
        } else {
            $reviews = $this->reviewModel->getByUser($userId);
        }

        $this->view('review.index', [
            'reviews' => $reviews,
            'filter' => $filter,
            'currentUserId' => $userId
        ]);
    }

    public function store()
    {
        $this->authCheck();

        $data = [
            'id_anime' => $this->input('id_anime'),
            'id_user' => Session::get('user_id'),
            'judul_anime' => $this->input('judul_anime'),
            'rating' => $this->input('rating'),
            'review_text' => $this->input('review_text'),
            'is_spoiler' => $this->input('is_spoiler') ? 1 : 0
        ];

        $errors = $this->validate($data, [
            'id_anime' => 'required',
            'rating' => 'required|numeric',
            'review_text' => 'required|min:10'
        ]);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            $this->redirect('anime/search');
        }

        $result = $this->reviewModel->createReview($data);

        if (isset($result['error'])) {
            Session::flash('error', $result['error']);
        } else {
            Session::flash('success', 'Review added successfully!');
        }

        $this->redirect('reviews');
    }

    public function edit($id)
    {
        $this->authCheck();

        $userId = Session::get('user_id');
        $review = $this->reviewModel->find($id);

        if (!$review || $review->id_user != $userId) {
            Session::flash('error', 'Review not found');
            $this->redirect('reviews');
        }

        $this->view('review.edit', [
            'review' => $review
        ]);
    }

    public function update($id)
    {
        $this->authCheck();

        $userId = Session::get('user_id');

        $data = [
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
            $this->redirect('reviews/edit/' . $id);
        }

        $this->reviewModel->updateReview($id, $data, $userId);

        Session::flash('success', 'Review updated successfully');
        $this->redirect('reviews');
    }

    public function delete($id)
    {
        $this->authCheck();

        $userId = Session::get('user_id');

        $this->reviewModel->deleteReview($id, $userId);

        Session::flash('success', 'Review deleted successfully');
        $this->redirect('reviews');
    }
}
