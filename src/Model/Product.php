<?php

declare(strict_types=1);

namespace App\Model;

final class Product
{
    public function __construct(
        private int $id,
        private string $name,
        private float $price,
        private int $categoryId,
        private int $stock
    ) {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID товара должен быть больше 0');
        }
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Название товара не может быть пустым');
        }
        if ($price < 0) {
            throw new \InvalidArgumentException('Цена не может быть отрицательной');
        }
        if ($categoryId <= 0) {
            throw new \InvalidArgumentException('categoryId должен быть больше 0');
        }
        if ($stock < 0) {
            throw new \InvalidArgumentException('Остаток не может быть отрицательным');
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function categoryId(): int
    {
        return $this->categoryId;
    }

    public function stock(): int
    {
        return $this->stock;
    }

    public function formatPrice(): string
    {
        return number_format($this->price, 0, '.', ' ') . ' ₽';
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function belongsToCategory(int $categoryId): bool
    {
        return $this->categoryId === $categoryId;
    }
}