<?php

use App\Core\View;

/** @var string $title */
/** @var string $content */
/** @var string $currentPath */
$currentPath = $currentPath ?? '';

$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cartCount += (int) $qty;
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= View::e($title) ?> — Учебный магазин</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
  <header class="site-header">
    <div class="wrap header-inner">
      <a class="brand" href="/">
        <img src="/assets/shop-logo.svg" alt="Логотип магазина" width="72" height="42">
        <span>Учебный магазин</span>
      </a>
      <nav class="nav">
        <a href="/" class="<?= $currentPath === '/' ? 'is-active' : '' ?>">Главная</a>
        <a href="/catalog" class="<?= $currentPath === '/catalog' ? 'is-active' : '' ?>">Каталог</a>
        <a href="/cart" class="<?= $currentPath === '/cart' ? 'is-active' : '' ?>">
          Корзина<?= $cartCount > 0 ? ' (' . View::e((string) $cartCount) . ')' : '' ?>
        </a>
        <a href="/about" class="<?= $currentPath === '/about' ? 'is-active' : '' ?>">О магазине</a>
      </nav>
    </div>
  </header>

  <main class="wrap">
    <?= $content ?>
  </main>

  <footer class="site-footer">
    <div class="wrap">
      <p>ТРПО · практика 9 · корзина и session</p>
    </div>
  </footer>
</body>
</html>