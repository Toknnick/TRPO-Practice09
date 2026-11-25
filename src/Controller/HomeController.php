<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;

final class HomeController
{
    public function __construct(
        private string $pagesPath
    ) {
    }

    public function index(): void
    {
        $pages = $this->loadPages();

        View::render('home/index', [
            'title' => $pages['store_name'] ?? 'Магазин',
            'storeName' => $pages['store_name'] ?? 'Магазин',
            'tagline' => $pages['tagline'] ?? '',
            'benefits' => $pages['benefits'] ?? [],
            'currentPath' => '/',
        ]);
    }

    public function about(): void
    {
        $pages = $this->loadPages();

        View::render('home/about', [
            'title' => 'О магазине',
            'storeName' => $pages['store_name'] ?? 'Магазин',
            'tagline' => $pages['tagline'] ?? '',
            'currentPath' => '/about',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function loadPages(): array
    {
        if (!is_file($this->pagesPath)) {
            return [];
        }

        $json = file_get_contents($this->pagesPath);
        if ($json === false) {
            return [];
        }

        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }
}