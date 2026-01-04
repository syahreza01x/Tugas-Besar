<?php

abstract class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);

        $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View {$view} not found");
        }
    }

    protected function redirect($path)
    {
        Helper::redirect($path);
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function input($key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function validate($data, $rules)
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $ruleList = explode('|', $rule);

            foreach ($ruleList as $singleRule) {
                $params = [];
                if (strpos($singleRule, ':') !== false) {
                    [$singleRule, $paramStr] = explode(':', $singleRule);
                    $params = explode(',', $paramStr);
                }

                $value = $data[$field] ?? null;

                switch ($singleRule) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$field][] = ucfirst($field) . ' is required';
                        }
                        break;
                    case 'email':
                        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = ucfirst($field) . ' must be a valid email';
                        }
                        break;
                    case 'min':
                        if ($value && strlen($value) < $params[0]) {
                            $errors[$field][] = ucfirst($field) . ' must be at least ' . $params[0] . ' characters';
                        }
                        break;
                    case 'max':
                        if ($value && strlen($value) > $params[0]) {
                            $errors[$field][] = ucfirst($field) . ' must not exceed ' . $params[0] . ' characters';
                        }
                        break;
                    case 'confirmed':
                        $confirmField = $field . '_confirmation';
                        if ($value !== ($data[$confirmField] ?? null)) {
                            $errors[$field][] = ucfirst($field) . ' confirmation does not match';
                        }
                        break;
                    case 'numeric':
                        if ($value && !is_numeric($value)) {
                            $errors[$field][] = ucfirst($field) . ' must be numeric';
                        }
                        break;
                }
            }
        }

        return $errors;
    }

    protected function authCheck()
    {
        if (!Session::isLoggedIn()) {
            Session::flash('error', 'Please login to continue');
            $this->redirect('login');
        }
    }

    protected function guestCheck()
    {
        if (Session::isLoggedIn()) {
            $this->redirect('dashboard');
        }
    }
}
