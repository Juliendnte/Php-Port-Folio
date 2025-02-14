<?php

namespace App\controllers;

class ErrorController
{
    public static function error404(): void
    {
        BaseController::render('error', ['message' => 'Page not found', 'code' => 404]);
    }

    public static function error500(): void
    {
        BaseController::render('error500', ['message' => 'Internal server error', 'code' => 500]);
    }
}