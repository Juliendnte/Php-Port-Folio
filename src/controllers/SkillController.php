<?php

namespace App\controllers;

use App\models\Skill;
use App\models\Users_skills;

class SkillController
{
    public static function addSkill(): void
    {
        $user = AuthController::connected();
        if (empty($user) || !UserController::isAdmin($user['id_role'])) {
            header('Location:/', 401);
        }
        $name = $_POST['name'];
        $skillModel = new Skill();
        $skillModel->addSkill($name);
        header('Location: /dashboard');
    }

    public static function deleteSkill(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user) || !UserController::isAdmin($user['id_role'])) {
            header('Location:/', 401);
        }

        $skillModel = new Skill();
        $skillModel->deleteSkill($id);

        $user_skilModel = new Users_skills();
        $user_skilModel->deleteUserSkill($user['id'], $id);

        header('Location: /dashboard');
    }

    public static function deleteUserSkill(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location:/', 401);
        }

        $userskillModel = new Users_skills();
        $userskillModel->deleteUserSkill($user['id'], $id);

        header('Location: /profile');
    }
    public static function updateUserSkill(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location:/', 401);
        }
        $level = $_POST['level'];

        $userskillModel = new Users_skills();
        $userskillModel->updateUserSkill($user['id'], $id, $level);

        header('Location: /profile');
    }

    public static function addUserSkill(): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location:/', 401);
        }

        $SkillId = $_POST['skill'];
        $Level = $_POST['level'];
        $user_skillModel = new Users_skills();
        $user_skillModel->addUserSkill($user['id'], $SkillId, $Level);
        header('Location: /profile');

    }
}