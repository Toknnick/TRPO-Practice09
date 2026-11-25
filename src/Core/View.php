<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    /**
     * @param array<string, mixed> $data
     */
    public static function render(string $view, array $data = [], string $layout = 'layout'): void
    {
        $viewFile = dirname(__DIR__, 2) . '/views/' . $view . '.php';
        $layoutFile = dirname(__DIR__, 2) . '/views/' . $layout . '.php';

        if (!is_file($viewFile)) {
            throw new \RuntimeException('Представление не найдено: ' . $view);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }

    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}