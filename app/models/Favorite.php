<?php

class Favorite extends Model
{
    protected $table = 'favorites';

    public function getByUser($userId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_user = :user_id 
                ORDER BY ranking ASC, created_at DESC";

        return $this->fetchAll($sql, ['user_id' => $userId]);
    }

    public function isFavorite($animeId, $userId)
    {
        $sql = "SELECT id FROM {$this->table} WHERE id_anime = :anime_id AND id_user = :user_id";
        return $this->fetch($sql, ['anime_id' => $animeId, 'user_id' => $userId]);
    }

    public function addFavorite($data)
    {
        if ($this->isFavorite($data['id_anime'], $data['id_user'])) {
            return ['error' => 'Already in favorites'];
        }

        $count = $this->count('*', 'id_user = :user_id', ['user_id' => $data['id_user']]);
        $data['ranking'] = $count + 1;

        return $this->create($data);
    }

    public function removeFavorite($animeId, $userId)
    {
        $sql = "DELETE FROM {$this->table} WHERE id_anime = :anime_id AND id_user = :user_id";
        return $this->query($sql, ['anime_id' => $animeId, 'user_id' => $userId]);
    }

    public function updateRanking($id, $ranking, $userId)
    {
        $sql = "UPDATE {$this->table} SET ranking = :ranking WHERE id = :id AND id_user = :user_id";
        return $this->query($sql, ['ranking' => $ranking, 'id' => $id, 'user_id' => $userId]);
    }

    public function getTopFavorites($userId, $limit = 5)
    {
        $limit = (int) $limit;
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_user = :user_id 
                ORDER BY ranking ASC 
                LIMIT {$limit}";

        return $this->fetchAll($sql, ['user_id' => $userId]);
    }
}
