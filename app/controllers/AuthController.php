<?php

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLogin()
    {
        $this->guestCheck();
        $this->view('auth.login');
    }

    public function login()
    {
        $this->guestCheck();

        $email = $this->input('email');
        $password = $this->input('password');

        $errors = $this->validate([
            'email' => $email,
            'password' => $password
        ], [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::set('old_input', ['email' => $email]);
            $this->redirect('login');
        }

        $user = $this->userModel->attempt($email, $password);

        if ($user) {
            Session::set('user_id', $user->id);
            Session::set('user_name', $user->name);
            Session::set('user_email', $user->email);
            Session::set('user_image', $user->image);

            Session::flash('success', 'Welcome back, ' . $user->name . '!');
            $this->redirect('dashboard');
        }

        Session::flash('error', 'Invalid email or password');
        Session::set('old_input', ['email' => $email]);
        $this->redirect('login');
    }

    public function showRegister()
    {
        $this->guestCheck();
        $this->view('auth.register');
    }

    public function register()
    {
        $this->guestCheck();

        $data = [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'password' => $this->input('password'),
            'password_confirmation' => $this->input('password_confirmation'),
            'gender' => $this->input('gender')
        ];

        $errors = $this->validate($data, [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'gender' => 'required'
        ]);

        // Check if email already exists
        if ($this->userModel->findByEmail($data['email'])) {
            $errors['email'][] = 'Email already registered';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::set('old_input', $data);
            $this->redirect('register');
        }

        unset($data['password_confirmation']);

        $userId = $this->userModel->register($data);

        if ($userId) {
            Session::flash('success', 'Registration successful! Please login.');
            $this->redirect('login');
        }

        Session::flash('error', 'Registration failed. Please try again.');
        $this->redirect('register');
    }

    public function logout()
    {
        Session::destroy();
        session_start();
        Session::flash('success', 'You have been logged out.');
        $this->redirect('login');
    }
}
