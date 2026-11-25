<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\Product;
use App\Repository\ProductRepository;

final class CatalogController
{
    public function __construct(
        private ProductRepository $products
    ) {
    }

    public function index(): void
    {
        $items = $this->products->all();
        $categories = $this->products->categories();

        $categoryFilter = trim($_GET['category'] ?? '');
        $sort = trim($_GET['sort'] ?? '');

        if ($categoryFilter !== '' && ctype_digit($categoryFilter)) {
            $categoryId = (int) $categoryFilter;
            $items = array_values(array_filter(
                $items,
                static fn(Product $product): bool => $product->belongsToCategory($categoryId)
            ));
        }

        if ($sort === 'price') {
            usort($items, static fn(Product $a, Product $b): int => $a->price() <=> $b->price());
        } elseif ($sort === 'name') {
            usort($items, static fn(Product $a, Product $b): int => $a->name() <=> $b->name());
        }

        $categoryNames = [];
        foreach ($categories as $category) {
            $categoryNames[$category->id()] = $category->name();
        }

        View::render('catalog/index', [
            'title' => 'Каталог',
            'items' => $items,
            'categories' => $categories,
            'categoryNames' => $categoryNames,
            'categoryFilter' => $categoryFilter,
            'sort' => $sort,
            'currentPath' => '/catalog',
        ]);
    }

    public function show(): void
    {
        $idRaw = trim($_GET['id'] ?? '');
        if ($idRaw === '' || !ctype_digit($idRaw)) {
            (new ErrorController())->notFound();
            return;
        }

        $product = $this->products->findById((int) $idRaw);
        if ($product === null) {
            (new ErrorController())->notFound();
            return;
        }

        $category = $this->products->findCategoryById($product->categoryId());

        View::render('catalog/show', [
            'title' => $product->name(),
            'product' => $product,
            'categoryName' => $category?->name() ?? 'Без категории',
            'currentPath' => '/catalog',
        ]);
    }
}
