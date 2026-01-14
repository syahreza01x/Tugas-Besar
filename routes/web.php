<?php

/**
 * Web Routes
 * Define all application routes here
 */

$router = $this->router;

// Home - redirect to login or dashboard
$router->get('', 'AuthController', 'showLogin');

// Authentication Routes
$router->get('login', 'AuthController', 'showLogin');
$router->post('login', 'AuthController', 'login');
$router->get('register', 'AuthController', 'showRegister');
$router->post('register', 'AuthController', 'register');
$router->get('logout', 'AuthController', 'logout');

// Dashboard
$router->get('dashboard', 'DashboardController', 'index');

// Anime Search
$router->get('anime/search', 'AnimeController', 'search');
$router->post('anime/add-to-list', 'AnimeController', 'addToList');
$router->post('anime/toggle-favorite', 'AnimeController', 'toggleFavorite');
$router->get('anime/detail/{id}', 'AnimeController', 'getAnimeDetail');

// Anime List
$router->get('list', 'ListController', 'index');
$router->get('list/{id}', 'ListController', 'show');
$router->post('list/update-status', 'ListController', 'updateStatus');
$router->post('list/update/{id}', 'ListController', 'update');
$router->post('list/delete/{id}', 'ListController', 'delete');
$router->post('list/toggle-favorite/{id}', 'ListController', 'toggleFavorite');
$router->post('list/review/{id}', 'ListController', 'storeReview');

// Favorites
$router->get('favorites', 'FavoriteController', 'index');
$router->post('favorites/update-ranking', 'FavoriteController', 'updateRanking');
$router->post('favorites/delete/{id}', 'FavoriteController', 'delete');

// Reviews
$router->get('reviews', 'ReviewController', 'index');
$router->post('reviews/store', 'ReviewController', 'store');
$router->get('reviews/edit/{id}', 'ReviewController', 'edit');
$router->post('reviews/update/{id}', 'ReviewController', 'update');
$router->post('reviews/delete/{id}', 'ReviewController', 'delete');

// Profile
$router->get('profile', 'ProfileController', 'index');
$router->post('profile/update', 'ProfileController', 'update');
