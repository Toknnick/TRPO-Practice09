<?php

use App\Core\View;
use App\Model\Product;

/** @var Product $product */
/** @var string $categoryName */
?>
<section class="page">
  <p class="eyebrow">Карточка товара</p>
  <h1><?= View::e($product->name()) ?></h1>
  <p>Категория: <?= View::e($categoryName) ?></p>
  <p class="price"><?= View::e($product->formatPrice()) ?></p>
  <p>Остаток: <?= View::e((string) $product->stock()) ?></p>
  <p>
    <?= $product->isInStock() ? 'В наличии' : 'Нет в наличии' ?>
  </p>
  <p><a class="button" href="/catalog">Назад в каталог</a></p>
</section>