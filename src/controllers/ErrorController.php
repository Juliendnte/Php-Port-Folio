<?php

namespace App\controllers;

class ErrorController
{
    public static function error404(): void
    {
        BaseController::render('error404');
    }
}