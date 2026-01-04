<?php

class Review extends Model
{
    protected $table = 'reviews';

    public function getByUser($userId)
    {
        $sql = "SELECT r.*, u.name as user_name, u.image as user_image 
                FROM {$this->table} r 
                JOIN users u ON r.id_user = u.id 
                WHERE r.id_user = :user_id 
                ORDER BY r.created_at DESC";

        return $this->fetchAll($sql, ['user_id' => $userId]);
    }

    public function getByAnime($animeId)
    {
        $sql = "SELECT r.*, u.name as user_name, u.image as user_image 
                FROM {$this->table} r 
                JOIN users u ON r.id_user = u.id 
                WHERE r.id_anime = :anime_id 
                ORDER BY r.created_at DESC";

        return $this->fetchAll($sql, ['anime_id' => $animeId]);
    }

    public function getRecentReviews($limit = 10)
    {
        $sql = "SELECT r.*, u.name as user_name, u.image as user_image 
                FROM {$this->table} r 
                JOIN users u ON r.id_user = u.id 
                ORDER BY r.created_at DESC 
                LIMIT :limit";

        return $this->fetchAll($sql, ['limit' => $limit]);
    }

    public function hasReviewed($animeId, $userId)
    {
        $sql = "SELECT id FROM {$this->table} WHERE id_anime = :anime_id AND id_user = :user_id";
        return $this->fetch($sql, ['anime_id' => $animeId, 'user_id' => $userId]);
    }

    public function createReview($data)
    {
        if ($this->hasReviewed($data['id_anime'], $data['id_user'])) {
            return ['error' => 'You have already reviewed this anime'];
        }

        return $this->create($data);
    }

    public function updateReview($id, $data, $userId)
    {
        $sql = "UPDATE {$this->table} SET 
                rating = :rating, 
                review_text = :review_text, 
                is_spoiler = :is_spoiler,
                updated_at = NOW() 
                WHERE id = :id AND id_user = :user_id";

        return $this->query($sql, [
            'rating' => $data['rating'],
            'review_text' => $data['review_text'],
            'is_spoiler' => $data['is_spoiler'],
            'id' => $id,
            'user_id' => $userId
        ]);
    }

    public function deleteReview($id, $userId)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id AND id_user = :user_id";
        return $this->query($sql, ['id' => $id, 'user_id' => $userId]);
    }

    public function getAverageRating($animeId)
    {
        $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                FROM {$this->table} WHERE id_anime = :anime_id";
        return $this->fetch($sql, ['anime_id' => $animeId]);
    }
}
