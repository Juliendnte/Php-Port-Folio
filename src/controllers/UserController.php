<?php

namespace App\controllers;

use App\models\Roles;
use App\models\User;

class UserController
{
    public static function profile()
    {

    }

    public static function dashboard()
    {
        $user = AuthController::connected();
        $userModel = new User();

        if (empty($user) || !self::isAdmin($user['id_role'])) {
            header('Location:/', 401);
        }

        BaseController::render('user/dashboard');
    }

    public static function isAdmin(int $id_role): bool
    {
        $roleModel = new Roles();
        return $id_role === $roleModel->getIdRole('admin')['id'];
    }
}