<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ProductRepository;

final class CartService
{
    public function __construct(
        private ProductRepository $products
    ) {
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function add(int $productId, int $qty = 1): void
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Количество должно быть больше 0');
        }
        if ($this->products->findById($productId) === null) {
            throw new \InvalidArgumentException('Товар не найден');
        }

        $current = (int) ($_SESSION['cart'][$productId] ?? 0);
        $_SESSION['cart'][$productId] = $current + $qty;
    }

    public function remove(int $productId): void
    {
        unset($_SESSION['cart'][$productId]);
    }

    public function setQuantity(int $productId, int $qty): void
    {
        if ($qty <= 0) {
            $this->remove($productId);
            return;
        }
        if ($this->products->findById($productId) === null) {
            throw new \InvalidArgumentException('Товар не найден');
        }

        $_SESSION['cart'][$productId] = $qty;
    }

    /**
     * @return array<int, array{product_id: int, name: string, price: float, qty: int, sum: float}>
     */
    public function lines(): array
    {
        $lines = [];

        foreach ($_SESSION['cart'] as $productId => $qty) {
            $productId = (int) $productId;
            $qty = (int) $qty;
            $product = $this->products->findById($productId);
            if ($product === null || $qty <= 0) {
                continue;
            }

            $lines[] = [
                'product_id' => $productId,
                'name' => $product->name(),
                'price' => $product->price(),
                'qty' => $qty,
                'sum' => $product->price() * $qty,
            ];
        }

        return $lines;
    }

    public function total(): float
    {
        $total = 0.0;
        foreach ($this->lines() as $line) {
            $total += $line['sum'];
        }
        return $total;
    }

    public function countItems(): int
    {
        $count = 0;
        foreach ($_SESSION['cart'] as $qty) {
            $count += (int) $qty;
        }
        return $count;
    }

    /**
     * @return array<int, int> product_id => qty
     */
    public function raw(): array
    {
        $raw = [];
        foreach ($_SESSION['cart'] as $productId => $qty) {
            $raw[(int) $productId] = (int) $qty;
        }
        return $raw;
    }
}