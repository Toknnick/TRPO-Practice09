<?php

use App\Core\View;

/** @var string $storeName */
/** @var string $tagline */
?>
<section class="page">
  <p class="eyebrow">О магазине</p>
  <h1><?= View::e($storeName) ?></h1>
  <p class="lead"><?= View::e($tagline) ?></p>
  <p>
    Заглушка.
  </p>
</section>