<?php

class ProfileController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        $this->authCheck();

        $userId = Session::get('user_id');
        $user = $this->userModel->find($userId);
        $stats = $this->userModel->getStats($userId);

        $this->view('profile.index', [
            'user' => $user,
            'stats' => $stats
        ]);
    }

    public function update()
    {
        $this->authCheck();

        $userId = Session::get('user_id');

        $data = [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'gender' => $this->input('gender')
        ];

        $password = $this->input('password');
        $passwordConfirmation = $this->input('password_confirmation');

        // Validation rules
        $rules = [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'gender' => 'required'
        ];

        // Add password validation if provided
        if (!empty($password)) {
            $data['password'] = $password;
            $data['password_confirmation'] = $passwordConfirmation;
            $rules['password'] = 'min:6|confirmed';
        }

        $errors = $this->validate($data, $rules);

        // Check if email already exists (exclude current user)
        $existingUser = $this->userModel->findByEmail($data['email']);
        if ($existingUser && $existingUser->id != $userId) {
            $errors['email'][] = 'Email already registered by another user';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            $this->redirect('profile');
        }

        // Update profile image if gender changed
        $currentUser = $this->userModel->find($userId);
        if ($currentUser->gender !== $data['gender']) {
            $data['image'] = Helper::getRandomProfileImage($data['gender']);
            Session::set('user_image', $data['image']);
        }

        unset($data['password_confirmation']);

        $this->userModel->updateProfile($userId, $data);

        // Update session
        Session::set('user_name', $data['name']);
        Session::set('user_email', $data['email']);

        Session::flash('success', 'Profile updated successfully');
        $this->redirect('profile');
    }
}
