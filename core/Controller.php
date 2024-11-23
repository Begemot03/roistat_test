<?php

namespace Core;

/**
 * Базовый класс для других контроллеров, определяет методы для указания эндпоинта и функции для его обработки
 * Включает методы для регистрации эндпоинтов, стандартные ответы для rest api
 * 
*/
abstract class Controller
{
    protected Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->defineEndpoints();
    }

    abstract protected function defineEndpoints(): void;

    protected function registerEndpoint(string $method, string $path, string $action): void
    {
        $this->router->register($method, $path, static::class, $action);
    }

    public function ok($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function error($msg, int $statusCode = 503): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode(['error' => $msg]);
    }
}
