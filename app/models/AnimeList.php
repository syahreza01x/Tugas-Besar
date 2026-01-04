<?php

class AnimeList extends Model
{
    protected $table = 'anime_lists';

    public function getByUser($userId)
    {
        $sql = "SELECT al.*, s.name as status_name, s.color as status_color 
                FROM {$this->table} al 
                LEFT JOIN statuses s ON al.status_id = s.id 
                WHERE al.id_user = :user_id 
                ORDER BY al.created_at DESC";

        return $this->fetchAll($sql, ['user_id' => $userId]);
    }

    public function getByUserAndStatus($userId, $statusId)
    {
        $sql = "SELECT al.*, s.name as status_name, s.color as status_color 
                FROM {$this->table} al 
                LEFT JOIN statuses s ON al.status_id = s.id 
                WHERE al.id_user = :user_id AND al.status_id = :status_id
                ORDER BY al.created_at DESC";

        return $this->fetchAll($sql, ['user_id' => $userId, 'status_id' => $statusId]);
    }

    public function findByAnimeAndUser($animeId, $userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_anime = :anime_id AND id_user = :user_id";
        return $this->fetch($sql, ['anime_id' => $animeId, 'user_id' => $userId]);
    }

    public function addToList($data)
    {
        $existing = $this->findByAnimeAndUser($data['id_anime'], $data['id_user']);

        if ($existing) {
            return ['error' => 'Anime already in your list'];
        }

        return $this->create($data);
    }

    public function updateStatus($id, $statusId, $userId)
    {
        $sql = "UPDATE {$this->table} SET status_id = :status_id, updated_at = NOW() 
                WHERE id = :id AND id_user = :user_id";
        return $this->query($sql, ['status_id' => $statusId, 'id' => $id, 'user_id' => $userId]);
    }

    public function updateProgress($id, $episodesWatched, $score, $notes, $userId)
    {
        $sql = "UPDATE {$this->table} SET 
                episodes_watched = :episodes, 
                score = :score, 
                notes = :notes,
                updated_at = NOW() 
                WHERE id = :id AND id_user = :user_id";

        return $this->query($sql, [
            'episodes' => $episodesWatched,
            'score' => $score,
            'notes' => $notes,
            'id' => $id,
            'user_id' => $userId
        ]);
    }

    public function getCountByStatus($userId)
    {
        $sql = "SELECT s.name, s.color, COUNT(al.id) as count 
                FROM statuses s 
                LEFT JOIN {$this->table} al ON s.id = al.status_id AND al.id_user = :user_id
                GROUP BY s.id, s.name, s.color";

        return $this->fetchAll($sql, ['user_id' => $userId]);
    }

    public function deleteFromList($id, $userId)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id AND id_user = :user_id";
        return $this->query($sql, ['id' => $id, 'user_id' => $userId]);
    }

    public function getDetailWithStatus($id, $userId)
    {
        $sql = "SELECT al.*, s.name as status_name, s.color as status_color 
                FROM {$this->table} al 
                LEFT JOIN statuses s ON al.status_id = s.id 
                WHERE al.id = :id AND al.id_user = :user_id";

        return $this->fetch($sql, ['id' => $id, 'user_id' => $userId]);
    }
}
