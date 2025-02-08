<?php

namespace App\controllers;

class BaseController
{
    public static function render($page, $variables = []): void
    {
        extract($variables);
        ob_start();

        include __DIR__ . "/../views/pages/$page.php";

        $content = ob_get_clean();

        include __DIR__ . "/../views/layout.php";
    }

}