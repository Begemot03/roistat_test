<?php
require_once __DIR__ . '/src/inc/config.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Request.php';
require_once __DIR__ . '/src/controllers/FeedbackController.php';

use Core\Router;
use App\Controllers\FeedbackController;

session_start();

function controllersResolve(): void
{
    $router = new Router();

    new FeedbackController($router);

    $router->resolve();
}

function main(): void
{
    // Если запрос на корневую страницу
    if ($_SERVER['REQUEST_URI'] == '/') {
        require __DIR__ . '/public/index.html';
        return;
    }

    // Проверяем, что запрос к api
    if (strpos($_SERVER['REQUEST_URI'], '/api') === 0) {
        // Проверка что запросы идут с клиента
        if (!isset($_SERVER['HTTP_ORIGIN']) && !isset($_SERVER['HTTP_REFERER'])) {
            header('Location: /');
            return;
        }
    } else {
        header('Location: /');
        return;
    }

    controllersResolve();
}

main();
