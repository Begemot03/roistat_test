<?php

namespace Core;

/**
 * Класс отвечает за обработку запроса и определения, какой обработчик эндоинта вызвать
 * Поддерживает динамические параметры запроса
 */
class Router
{
    private array $routes = [];

    public function register(string $method, string $path, string $controller, string $action): void
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $path);
        $this->routes[strtoupper($method)][$pattern] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function resolve()
    {
        $request = new Request();

        if (isset($this->routes[$request->method])) {
            foreach ($this->routes[$request->method] as $pattern => $route) {
                if (preg_match("#^$pattern$#", $request->path, $matches)) {
                    $controllerName = $route['controller'];
                    $action = $route['action'];
                    
                    if (class_exists($controllerName)) {
                        $controllerInstance = new $controllerName($this);

                        if (method_exists($controllerInstance, $action)) {
                            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                            
                            return call_user_func([$controllerInstance, $action], $request, ...$params);
                        }
                    }

                    http_response_code(404);
                    echo json_encode(["error" => "Action not found"]);
                    return;
                }
            }
        }
        
        http_response_code(404);
        echo json_encode(["error" => "Not Found"]);
    }
}
