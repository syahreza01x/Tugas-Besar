<?php

class Router
{
    private $routes = [];
    private $params = [];

    public function add($route, $controller, $action, $method = 'GET')
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z0-9-]+)', $route);
        $route = '/^' . $route . '$/i';

        // Use method + route as key to allow same URL with different methods
        $key = $method . ':' . $route;

        $this->routes[$key] = [
            'controller' => $controller,
            'action' => $action,
            'method' => $method,
            'pattern' => $route
        ];
    }

    public function get($route, $controller, $action)
    {
        $this->add($route, $controller, $action, 'GET');
    }

    public function post($route, $controller, $action)
    {
        $this->add($route, $controller, $action, 'POST');
    }

    public function match($url)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        
        foreach ($this->routes as $key => $params) {
            $pattern = $params['pattern'];
            
            // Only match routes with the same HTTP method
            if ($params['method'] !== $requestMethod) {
                continue;
            }
            
            if (preg_match($pattern, $url, $matches)) {
                foreach ($matches as $k => $match) {
                    if (is_string($k)) {
                        $params[$k] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function dispatch($url)
    {
        $url = $this->removeQueryString($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $action = $this->params['action'];

            if (class_exists($controller)) {
                $controllerObj = new $controller();

                if (method_exists($controllerObj, $action)) {
                    unset($this->params['controller'], $this->params['action'], $this->params['method'], $this->params['pattern']);
                    call_user_func_array([$controllerObj, $action], $this->params);
                } else {
                    http_response_code(404);
                    echo "Action {$action} not found in {$controller}";
                }
            } else {
                http_response_code(404);
                echo "Controller {$controller} not found";
            }
        } else {
            http_response_code(404);
            require_once __DIR__ . '/../app/views/errors/404.php';
        }
    }

    private function removeQueryString($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return rtrim($url, '/');
    }

    public function getParams()
    {
        return $this->params;
    }
}
