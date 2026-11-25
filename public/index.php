<?php

declare(strict_types=1);

$root = dirname(__DIR__);

require_once $root . '/src/autoload.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

use App\Controller\CartController;
use App\Controller\CatalogController;
use App\Controller\ErrorController;
use App\Controller\HomeController;
use App\Core\Router;
use App\Repository\ProductRepository;
use App\Service\CartService;

$productRepository = new ProductRepository(
    $root . '/data/products.json',
    $root . '/data/categories.json'
);
$cartService = new CartService($productRepository);

$router = Router::fromCsv($root . '/data/routes.csv');
$router->setControllers([
    'HomeController' => new HomeController($root . '/data/pages.json'),
    'CatalogController' => new CatalogController($productRepository),
    'CartController' => new CartController($cartService),
    'ErrorController' => new ErrorController(),
]);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if (!is_string($uri) || $uri === '') {
    $uri = '/';
}

$router->dispatch($method, $uri);