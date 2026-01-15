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
        // Unset all session variables
        $_SESSION = [];
        
        // Delete the session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        // Destroy the session
        session_destroy();
    }

    /**
     * Regenerate session ID to prevent session fixation attacks
     * Call this after successful login
     */
    public static function regenerate()
    {
        // Regenerate session ID and delete old session
        session_regenerate_id(true);
        
        // Also regenerate CSRF token
        self::remove('csrf_token');
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
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        // Validate session fingerprint to prevent session hijacking
        $currentUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $storedUserAgent = $_SESSION['user_agent'] ?? '';
        
        // If user agent doesn't match, invalidate session
        if ($storedUserAgent && $currentUserAgent !== $storedUserAgent) {
            self::destroy();
            self::start();
            return false;
        }
        
        return true;
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
