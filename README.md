# AnimeList - PHP Native MVC Project

Aplikasi web untuk tracking anime yang ingin ditonton menggunakan PHP Native dengan konsep OOP dan MVC.

## Features

- 🔐 **Authentication** - Register & Login dengan profile picture berdasarkan gender
- 🔍 **Search Anime** - Mencari anime menggunakan Jikan API (MyAnimeList)
- 📝 **Anime List** - Menambahkan anime ke daftar dengan berbagai status
- ⭐ **Reviews** - Menulis review dan rating untuk anime
- ❤️ **Favorites** - Menyimpan anime favorit
- 👤 **Profile** - Mengelola profil pengguna

## Database Structure (5 Tables)

1. **users** - Data pengguna
2. **statuses** - Status anime (Plan to Watch, Watching, Completed, etc.)
3. **anime_lists** - Daftar anime pengguna
4. **reviews** - Review anime dari pengguna
5. **favorites** - Anime favorit pengguna

## Tech Stack

- PHP 8.x (Native dengan OOP)
- MySQL/MariaDB
- Bootstrap 5
- jQuery
- Font Awesome
- Jikan API v4

## Installation

### 1. Clone Repository
```bash
git clone [repository-url]
cd Tugas-Besar
```

### 2. Setup Database
```bash
# Buat database baru
mysql -u root -p -e "CREATE DATABASE tugas_besar"

# Import schema
mysql -u root -p tugas_besar < database/schema.sql
```

### 3. Configure Database
Edit file `config/database.php`:
```php
return [
    'host' => 'localhost',
    'database' => 'tugas_besar',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];
```

### 4. Setup Profile Images
Letakkan 5 gambar profile di `assets/img/`:
- `profile_1.png` - Untuk Pria (random)
- `profile_2.png` - Untuk Pria (random)
- `profile_3.png` - Untuk Wanita (random)
- `profile_4.png` - Untuk Wanita (random)
- `profile_5.png` - Untuk Wanita (random)

### 5. Run Application
Jika menggunakan Laragon, akses:
```
http://localhost/Tugas-Besar
```

## Project Structure

```
Tugas-Besar/
├── app/
│   ├── controllers/       # Controllers
│   │   ├── Controller.php
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── AnimeController.php
│   │   ├── ListController.php
│   │   ├── ReviewController.php
│   │   ├── FavoriteController.php
│   │   └── ProfileController.php
│   ├── models/            # Models
│   │   ├── Model.php
│   │   ├── User.php
│   │   ├── Status.php
│   │   ├── AnimeList.php
│   │   ├── Review.php
│   │   └── Favorite.php
│   └── views/             # Views
│       ├── layouts/
│       ├── auth/
│       ├── dashboard/
│       ├── anime/
│       ├── list/
│       ├── review/
│       ├── favorite/
│       ├── profile/
│       └── errors/
├── assets/
│   └── img/               # Profile images
├── config/
│   └── database.php       # Database configuration
├── core/
│   ├── App.php           # Application bootstrap
│   ├── Database.php      # Database connection (Singleton)
│   ├── Router.php        # URL routing
│   ├── Session.php       # Session management
│   └── Helper.php        # Helper functions
├── database/
│   └── schema.sql        # Database schema
├── public/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── app.js
├── routes/
│   └── web.php           # Route definitions
├── .htaccess             # Apache rewrite rules
├── index.php             # Entry point
└── README.md
```

## API Reference

Aplikasi ini menggunakan [Jikan API v4](https://docs.api.jikan.moe/) untuk mengambil data anime dari MyAnimeList.

### Endpoints yang digunakan:
- `GET /anime?q={query}&sfw` - Mencari anime
- `GET /anime/{id}` - Detail anime

## Routes

| Method | URI | Controller | Action |
|--------|-----|------------|--------|
| GET | / | AuthController | showLogin |
| GET/POST | /login | AuthController | showLogin/login |
| GET/POST | /register | AuthController | showRegister/register |
| GET | /logout | AuthController | logout |
| GET | /dashboard | DashboardController | index |
| GET | /anime/search | AnimeController | search |
| POST | /anime/add-to-list | AnimeController | addToList |
| POST | /anime/toggle-favorite | AnimeController | toggleFavorite |
| GET | /list | ListController | index |
| GET | /list/{id} | ListController | show |
| POST | /list/update/{id} | ListController | update |
| POST | /list/delete/{id} | ListController | delete |
| GET | /favorites | FavoriteController | index |
| GET | /reviews | ReviewController | index |
| GET | /profile | ProfileController | index |

## Screenshots

[Add screenshots here]

## License

MIT License

## Author

Tugas Besar - Web Programming
