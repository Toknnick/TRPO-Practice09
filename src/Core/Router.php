<?php

declare(strict_types=1);

namespace App\Core;

use App\Controller\ErrorController;

final class Router
{
    /** @var array<int, array{method: string, path: string, controller: string, action: string}> */
    private array $routes;

    /** @var array<string, object> */
    private array $controllers = [];

    /**
     * @param array<int, array{method: string, path: string, controller: string, action: string}> $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public static function fromCsv(string $csvPath): self
    {
        if (!is_file($csvPath)) {
            throw new \InvalidArgumentException('Файл маршрутов не найден: ' . $csvPath);
        }

        $file = fopen($csvPath, 'r');
        if ($file === false) {
            throw new \RuntimeException('Не удалось открыть routes.csv');
        }

        fgetcsv($file, 0, ';');
        $routes = [];

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            if (count($row) < 4) {
                continue;
            }

            $routes[] = [
                'method' => strtoupper(trim($row[0])),
                'path' => trim($row[1]),
                'controller' => trim($row[2]),
                'action' => trim($row[3]),
            ];
        }

        fclose($file);

        return new self($routes);
    }

    /**
     * @param array<string, object> $controllers
     */
    public function setControllers(array $controllers): void
    {
        $this->controllers = $controllers;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if ($route['path'] !== $uri) {
                continue;
            }

            $this->call($route['controller'], $route['action']);
            return;
        }

        $error = $this->controllers['ErrorController'] ?? new ErrorController();
        $error->notFound();
    }

    private function call(string $controllerName, string $action): void
    {
        if (!isset($this->controllers[$controllerName])) {
            throw new \RuntimeException('Контроллер не зарегистрирован: ' . $controllerName);
        }

        $controller = $this->controllers[$controllerName];

        if (!method_exists($controller, $action)) {
            throw new \RuntimeException('Метод не найден: ' . $controllerName . '::' . $action);
        }

        $controller->{$action}();
    }
}