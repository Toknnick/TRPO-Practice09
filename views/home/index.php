<?php

use App\Core\View;

/** @var string $storeName */
/** @var string $tagline */
/** @var array<int, string> $benefits */
?>
<section class="hero">
  <p class="eyebrow">Главная</p>
  <h1><?= View::e($storeName) ?></h1>
  <p class="lead"><?= View::e($tagline) ?></p>
  <p><a class="button" href="/catalog">Открыть каталог</a></p>
</section>

<section class="benefits">
  <h2>Преимущества</h2>
  <ul class="benefit-list">
    <?php foreach ($benefits as $benefit): ?>
      <li><?= View::e($benefit) ?></li>
    <?php endforeach; ?>
  </ul>
</section>