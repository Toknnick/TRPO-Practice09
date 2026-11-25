<?php

use App\Core\View;

/** @var array<int, array{product_id: int, name: string, price: float, qty: int, sum: float}> $lines */
/** @var float $total */
/** @var string|null $flash */
?>
<section class="page">
  <p class="eyebrow">Корзина</p>
  <h1>Ваш заказ</h1>

  <?php if ($flash): ?>
    <p class="flash"><?= View::e($flash) ?></p>
  <?php endif; ?>

  <?php if ($lines === []): ?>
    <p>Корзина пуста.</p>
    <p><a class="button" href="/catalog">Перейти в каталог</a></p>
  <?php else: ?>
    <table class="cart-table">
      <thead>
        <tr>
          <th>Товар</th>
          <th>Цена</th>
          <th>Кол-во</th>
          <th>Сумма</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lines as $line): ?>
          <tr>
            <td><?= View::e($line['name']) ?></td>
            <td><?= View::e(number_format($line['price'], 0, '.', ' ')) ?> ₽</td>
            <td>
              <form method="post" action="/cart/update" class="inline-form">
                <input type="hidden" name="product_id" value="<?= View::e((string) $line['product_id']) ?>">
                <input type="number" name="qty" min="1" value="<?= View::e((string) $line['qty']) ?>">
                <button type="submit">OK</button>
              </form>
            </td>
            <td><?= View::e(number_format($line['sum'], 0, '.', ' ')) ?> ₽</td>
            <td>
              <form method="post" action="/cart/remove" class="inline-form">
                <input type="hidden" name="product_id" value="<?= View::e((string) $line['product_id']) ?>">
                <button type="submit">Удалить</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <p class="price">Итого: <?= View::e(number_format($total, 0, '.', ' ')) ?> ₽</p>
  <?php endif; ?>
</section>