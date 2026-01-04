-- Database: tugas_besar
-- Minimal 5 tables

CREATE DATABASE IF NOT EXISTS tugas_besar;
USE tugas_besar;

-- Table 1: users
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender VARCHAR(255),
    image VARCHAR(255),
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 2: statuses (untuk status anime list)
CREATE TABLE IF NOT EXISTS statuses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    color VARCHAR(50) DEFAULT '#6c757d',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default statuses
INSERT INTO statuses (name, color) VALUES 
('Plan to Watch', '#17a2b8'),
('Watching', '#ffc107'),
('Completed', '#28a745'),
('On Hold', '#fd7e14'),
('Dropped', '#dc3545');

-- Table 3: anime_lists (list anime user)
CREATE TABLE IF NOT EXISTS anime_lists (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_anime VARCHAR(255) NOT NULL,
    id_user BIGINT UNSIGNED NOT NULL,
    judul VARCHAR(255) NOT NULL,
    sinopsis TEXT,
    studio VARCHAR(255),
    genre VARCHAR(255),
    gambar VARCHAR(255),
    status_id BIGINT UNSIGNED DEFAULT 1,
    score INT DEFAULT NULL,
    episodes_watched INT DEFAULT 0,
    total_episodes INT DEFAULT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (status_id) REFERENCES statuses(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 4: reviews (review anime dari user)
CREATE TABLE IF NOT EXISTS reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_anime VARCHAR(255) NOT NULL,
    id_user BIGINT UNSIGNED NOT NULL,
    judul_anime VARCHAR(255) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 10),
    review_text TEXT NOT NULL,
    is_spoiler BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 5: favorites (anime favorit user)
CREATE TABLE IF NOT EXISTS favorites (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_anime VARCHAR(255) NOT NULL,
    id_user BIGINT UNSIGNED NOT NULL,
    judul VARCHAR(255) NOT NULL,
    gambar VARCHAR(255),
    ranking INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (id_anime, id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index untuk performa
CREATE INDEX idx_anime_lists_user ON anime_lists(id_user);
CREATE INDEX idx_reviews_user ON reviews(id_user);
CREATE INDEX idx_favorites_user ON favorites(id_user);
