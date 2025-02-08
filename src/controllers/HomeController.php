<?php

namespace App\controllers;

use App\models\User;

class HomeController
{
    public function index(): void
    {
        $userModel = new User();

        BaseController::render('home', [
            'users' => $userModel->findAllUsers(),
        ]);
    }

}