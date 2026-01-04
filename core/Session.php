<?php

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy()
    {
        session_destroy();
        $_SESSION = [];
    }

    public static function flash($key, $value = null)
    {
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
        } else {
            $flashValue = $_SESSION['_flash'][$key] ?? null;
            unset($_SESSION['_flash'][$key]);
            return $flashValue;
        }
    }

    public static function hasFlash($key)
    {
        return isset($_SESSION['_flash'][$key]);
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public static function user()
    {
        if (self::isLoggedIn()) {
            return (object) [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'image' => $_SESSION['user_image'] ?? null
            ];
        }
        return null;
    }
}
