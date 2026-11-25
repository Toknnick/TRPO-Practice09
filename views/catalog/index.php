<?php

use App\Core\View;
use App\Model\Category;
use App\Model\Product;

/** @var Product[] $items */
/** @var Category[] $categories */
/** @var array<int, string> $categoryNames */
/** @var string $categoryFilter */
/** @var string $sort */
?>
<section class="page">
  <p class="eyebrow">Каталог</p>
  <h1>Товары</h1>

  <form method="get" action="/catalog" class="catalog-filters">
    <label>
      Категория
      <select name="category">
        <option value="">Все</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= View::e((string) $category->id()) ?>"<?= $categoryFilter === (string) $category->id() ? ' selected' : '' ?>>
            <?= View::e($category->name()) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label>
      Сортировка
      <select name="sort">
        <option value="">По умолчанию</option>
        <option value="price"<?= $sort === 'price' ? ' selected' : '' ?>>По цене</option>
        <option value="name"<?= $sort === 'name' ? ' selected' : '' ?>>По названию</option>
      </select>
    </label>

    <button type="submit">Показать</button>
  </form>

  <?php if ($items === []): ?>
    <p>Ничего не найдено.</p>
  <?php else: ?>
    <div class="product-grid">
      <?php foreach ($items as $item): ?>
        <article class="product-card">
          <h2>
            <a href="/catalog/product?id=<?= View::e((string) $item->id()) ?>">
              <?= View::e($item->name()) ?>
            </a>
          </h2>
          <p><?= View::e($categoryNames[$item->categoryId()] ?? 'Без категории') ?></p>
          <p class="price"><?= View::e($item->formatPrice()) ?></p>
          <p>Остаток: <?= View::e((string) $item->stock()) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>