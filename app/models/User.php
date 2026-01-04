<?php

class User extends Model
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }

    public function register($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['image'] = Helper::getRandomProfileImage($data['gender']);
        return $this->create($data);
    }

    public function attempt($email, $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }

    public function updateProfile($id, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        return $this->update($id, $data);
    }

    public function getStats($userId)
    {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM anime_lists WHERE id_user = :id1) as total_anime,
                    (SELECT COUNT(*) FROM anime_lists WHERE id_user = :id2 AND status_id = 3) as completed,
                    (SELECT COUNT(*) FROM anime_lists WHERE id_user = :id3 AND status_id = 2) as watching,
                    (SELECT COUNT(*) FROM reviews WHERE id_user = :id4) as total_reviews,
                    (SELECT COUNT(*) FROM favorites WHERE id_user = :id5) as total_favorites";

        return $this->fetch($sql, [
            'id1' => $userId,
            'id2' => $userId,
            'id3' => $userId,
            'id4' => $userId,
            'id5' => $userId
        ]);
    }
}
