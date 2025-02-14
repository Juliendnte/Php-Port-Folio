<?php

namespace App\controllers;

use App\models\Skill;
use App\models\Users_skills;

/**
 * Contrôleur des compétences (skills).
 *
 * Gère les actions liées aux compétences : ajout, suppression et mise à jour,
 * aussi bien pour les compétences globales que pour les compétences de l'utilisateur.
 */
class SkillController
{
    /**
     * Ajoute une compétence globale au système.
     *
     * Cette méthode est réservée aux administrateurs. Si l'utilisateur n'est pas connecté ou
     * ne dispose pas des droits `admin`, il est redirigé vers la page d'accueil avec une erreur 401.
     *
     * @return void
     */
    public static function addSkill(): void
    {
        $user = AuthController::connected();
        if (empty($user) || !UserController::isAdmin($user['id_role'])) {
            header('Location:/', 401);
        }
        $name = $_POST['name'];
        $skillModel = new Skill();
        if (empty($skillModel->addSkill($name))) {
            $_SESSION['error']['duplicata'] = 'Ce skill existe déjà';
        }
        header('Location: /dashboard');
    }

    /**
     * Supprime une compétence globale.
     *
     * Cette méthode est réservée aux administrateurs. Si l'utilisateur n'est pas connecté ou
     * ne dispose pas des droits `admin`, il est redirigé vers la page d'accueil avec une erreur 401.
     * La méthode supprime également la compétence associée à l'utilisateur concerné.
     *
     * @param int $id ID de la compétence à supprimer.
     *
     * @return void
     */
    public static function deleteSkill(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user) || !UserController::isAdmin($user['id_role'])) {
            header('Location:/', 401);
        }

        $skillModel = new Skill();
        if ($skillModel->deleteSkill($id) !== null) {
            $user_skilModel = new Users_skills();
            $user_skilModel->deleteUserSkill($user['id'], $id);
        }

        header('Location: /dashboard');
    }

    /**
     * Supprime une compétence utilisateur spécifique.
     *
     * Vérifie si l'utilisateur est connecté et autorisé à supprimer la compétence utilisateur. Si la compétence n'existe pas
     * ou si l'utilisateur n'est pas connecté, il est redirigé avec une erreur 401.
     *
     * @param int $id ID de la compétence utilisateur à supprimer.
     *
     * @return void
     */
    public static function deleteUserSkill(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location:/', 401);
        }

        $userskillModel = new Users_skills();
        $skill = $userskillModel->getUsersSkills($user['id'], $id);
        if (empty($skill)) {
            header('Location:/', 401);
            exit();
        }
        $userskillModel->deleteUserSkill($user['id'], $id);

        header('Location: /profile');
    }

    /**
     * Met à jour le niveau d'une compétence utilisateur.
     *
     * Vérifie l'authentification de l'utilisateur. Si l'utilisateur n'a pas accès
     * ou si la compétence utilisateur n'existe pas, il est redirigé avec une erreur 401.
     *
     * @param int $id ID de la compétence utilisateur à mettre à jour.
     *
     * @return void
     */
    public static function updateUserSkill(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location:/', 401);
        }
        $level = $_POST['level'];

        $userskillModel = new Users_skills();
        $skill = $userskillModel->getUsersSkills($user['id'], $id);
        if (empty($skill)) {
            header('Location:/', 401);
            exit();
        }
        $userskillModel->updateUserSkill($user['id'], $id, $level);

        header('Location: /profile');
    }

    /**
     * Ajoute une compétence à l'utilisateur connecté.
     *
     * Vérifie que l'utilisateur est connecté, puis ajoute la compétence (avec le niveau spécifié) à son profil.
     * Si l'utilisateur n'est pas connecté, il est redirigé avec une erreur 401.
     *
     * @return void
     */
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