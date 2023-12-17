<?php
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        require __DIR__ . '/view/home/index.php';
        break;
    case '/click':
        require __DIR__ . '/controller/HomeController.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/view/404.php';
        break;
}
