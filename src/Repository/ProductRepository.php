<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Category;
use App\Model\Product;

final class ProductRepository
{
    public function __construct(
        private string $productsFile,
        private string $categoriesFile
    ) {
    }

    /**
     * @return Product[]
     */
    public function all(): array
    {
        $rows = $this->readJson($this->productsFile);
        $products = [];

        foreach ($rows as $row) {
            $products[] = new Product(
                (int) $row['id'],
                (string) $row['name'],
                (float) $row['price'],
                (int) ($row['category_id'] ?? 0),
                (int) ($row['stock'] ?? 0)
            );
        }

        return $products;
    }

    public function findById(int $id): ?Product
    {
        foreach ($this->all() as $product) {
            if ($product->id() === $id) {
                return $product;
            }
        }

        return null;
    }

    /**
     * @return Category[]
     */
    public function categories(): array
    {
        if (!is_file($this->categoriesFile)) {
            return [];
        }

        $rows = $this->readJson($this->categoriesFile);
        $categories = [];

        foreach ($rows as $row) {
            $categories[] = new Category(
                (int) $row['id'],
                (string) $row['name']
            );
        }

        return $categories;
    }

    public function findCategoryById(int $id): ?Category
    {
        foreach ($this->categories() as $category) {
            if ($category->id() === $id) {
                return $category;
            }
        }

        return null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function readJson(string $path): array
    {
        if (!is_file($path)) {
            throw new \RuntimeException('Файл не найден: ' . $path);
        }

        $json = file_get_contents($path);
        if ($json === false) {
            throw new \RuntimeException('Не удалось прочитать: ' . $path);
        }

        $data = json_decode($json, true);
        if (!is_array($data)) {
            throw new \RuntimeException('Некорректный JSON: ' . $path);
        }

        return $data;
    }
}