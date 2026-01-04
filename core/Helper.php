<?php

class Helper
{
    public static function baseUrl($path = '')
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $baseUrl = $protocol . '://' . $host . $scriptName;
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }

    public static function redirect($path)
    {
        header('Location: ' . self::baseUrl($path));
        exit;
    }

    public static function asset($path)
    {
        return self::baseUrl('public/' . ltrim($path, '/'));
    }

    public static function old($key, $default = '')
    {
        return Session::get('old_input')[$key] ?? $default;
    }

    public static function csrf()
    {
        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('csrf_token');
    }

    public static function csrfField()
    {
        return '<input type="hidden" name="csrf_token" value="' . self::csrf() . '">';
    }

    public static function verifyCsrf($token)
    {
        return hash_equals(Session::get('csrf_token'), $token);
    }

    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    public static function formatDate($date, $format = 'd M Y')
    {
        return date($format, strtotime($date));
    }

    public static function truncate($string, $length = 100)
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length) . '...';
        }
        return $string;
    }

    public static function getRandomProfileImage($gender)
    {
        if (strtolower($gender) === 'pria' || strtolower($gender) === 'male') {
            $num = rand(1, 2);
        } else {
            $num = rand(3, 5);
        }
        return "{$num}.png";
    }
}

// Helper functions for easier use in views
function baseUrl($path = '')
{
    return Helper::baseUrl($path);
}

function redirect($path)
{
    return Helper::redirect($path);
}

function asset($path)
{
    return Helper::asset($path);
}

function old($key, $default = '')
{
    return Helper::old($key, $default);
}

function csrf()
{
    return Helper::csrf();
}

function csrfField()
{
    return Helper::csrfField();
}

function e($string)
{
    return Helper::escape($string);
}
