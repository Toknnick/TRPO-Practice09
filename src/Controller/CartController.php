<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Service\CartService;

final class CartController
{
    public function __construct(
        private CartService $cart
    ) {
    }

    public function index(): void
    {
        View::render('cart/index', [
            'title' => 'Корзина',
            'lines' => $this->cart->lines(),
            'total' => $this->cart->total(),
            'countItems' => $this->cart->countItems(),
            'flash' => $this->pullFlash(),
            'currentPath' => '/cart',
        ]);
    }

    public function add(): void
    {
        try {
            $productId = (int) ($_POST['product_id'] ?? 0);
            $this->cart->add($productId, 1);
            $this->setFlash('Товар добавлен в корзину');
        } catch (\InvalidArgumentException $e) {
            $this->setFlash($e->getMessage());
        }

        $this->redirect('/cart');
    }

    public function update(): void
    {
        try {
            $productId = (int) ($_POST['product_id'] ?? 0);
            $qty = (int) ($_POST['qty'] ?? 0);
            $this->cart->setQuantity($productId, $qty);
            $this->setFlash('Количество обновлено');
        } catch (\InvalidArgumentException $e) {
            $this->setFlash($e->getMessage());
        }

        $this->redirect('/cart');
    }

    public function remove(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $this->cart->remove($productId);
        $this->setFlash('Товар удалён из корзины');
        $this->redirect('/cart');
    }

    private function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    private function setFlash(string $message): void
    {
        $_SESSION['_flash'] = $message;
    }

    private function pullFlash(): ?string
    {
        if (!isset($_SESSION['_flash'])) {
            return null;
        }
        $message = (string) $_SESSION['_flash'];
        unset($_SESSION['_flash']);
        return $message;
    }
}
