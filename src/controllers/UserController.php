<?php

namespace App\controllers;

use App\models\Project;
use App\models\Roles;
use App\models\Skill;
use App\models\User;
use App\models\Users_skills;

class UserController
{
    public static function profile(): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location:/', 401);
        }
        $projectModel = new Project();
        $project = $projectModel->findAllProjectsByUser($user['id']);

        $userskillModel = new Users_skills();
        $skillModel = new Skill();
        $skills = $skillModel->getAllSkills();
        $skillsUsers = $userskillModel->getAllUsersSkillsByUserId($user['id']);
        BaseController::render('user/profile', ['user' => $user, 'project' => $project, 'skills' => $skillsUsers, 'availableSkills' => $skills]);
    }

    public static function dashboard(): void
    {
        $user = AuthController::connected();
        if (empty($user) || !self::isAdmin($user['id_role'])) {
            header('Location:/', 401);
        }
        $skillModel = new Skill();
        $skills = $skillModel->getAllSkills();
        BaseController::render('user/dashboard', ['skills' => $skills]);
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
        $username = !empty($_POST['username']) ? $_POST['username'] : $user['username'];
        $email = !empty($_POST['email']) ? $_POST['email'] : $user['email'];
        $password = !empty($_POST['password']) ? $_POST['password'] : $user['password'];

        $userModel = new User();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $values = ["username" => $username,
            'password' => $hashedPassword === $user['password'] ? $password : $hashedPassword,
            "email" => $email];
        $userModel->update($user['id'], $values);
        header('Location:/profile');
    }
}