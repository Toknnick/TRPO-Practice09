<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;

final class ErrorController
{
    public function notFound(): void
    {
        http_response_code(404);

        View::render('errors/404', [
            'title' => 'Страница не найдена',
            'currentPath' => '',
        ]);
    }
}