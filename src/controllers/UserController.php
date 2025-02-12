<?php

namespace App\controllers;

use App\models\Roles;
use App\models\User;

class UserController
{
    public static function profile(): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location:/', 401);
        }
        BaseController::render('user/profile', $user);
    }

    public static function dashboard(): void
    {
        $user = AuthController::connected();
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

    public static function updateUser(): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location:/', 401);
        }
        BaseController::render('user/update', $user);
    }

    public static function update(): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location:/', 401);
        }
        $username = $_POST['username'] ?? $user['username'];
        $email = $_POST['email'] ?? $user['email'];
        $password = $_POST['password'] ?? $user['password'];

        $userModel = new User();
        $userModel->update($user['id'], ['username' => $username, 'email' => $email, 'password' => $password === $user['password'] ? $password : password_hash($password, PASSWORD_DEFAULT)]);
        header('Location:/user/profile');
    }
}