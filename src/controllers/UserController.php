<?php

namespace App\controllers;

use App\models\Project;
use App\models\Roles;
use App\models\Skill;
use App\models\User;
use App\models\Users_skills;

/**
 * Contrôleur des utilisateurs.
 *
 * Gère les actions relatives aux utilisateurs, comme afficher leur profil,
 * accéder au tableau de bord (pour les administrateurs), et mettre à jour leurs informations.
 */
class UserController
{
    /**
     * Affiche le profil de l'utilisateur connecté.
     *
     * Charge les projets et les compétences de l'utilisateur, ainsi que
     * les compétences disponibles pour permettre l'ajout ou la modification.
     * Si l'utilisateur n'est pas connecté, il est redirigé avec une erreur 401.
     *
     * @return void
     */
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

    /**
     * Affiche le tableau de bord pour les administrateurs.
     *
     * Vérifie que l'utilisateur est connecté et dispose des droits administratifs.
     * Charge toutes les compétences disponibles dans le système.
     * Si l'utilisateur n'est pas administrateur, il est redirigé avec une erreur 401.
     *
     * @return void
     */
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

    /**
     * Vérifie si un utilisateur est administrateur.
     *
     * Vérifie si le rôle de l'utilisateur correspond au rôle "admin".
     *
     * @param int $id_role Identifiant du rôle de l'utilisateur.
     *
     * @return bool Retourne `true` si l'utilisateur est administrateur, sinon `false`.
     */
    public static function isAdmin(int $id_role): bool
    {
        $roleModel = new Roles();
        return $id_role === $roleModel->getIdRole('admin')['id'];
    }

    /**
     * Affiche le formulaire pour mettre à jour les informations de l'utilisateur.
     *
     * Charge les informations de l'utilisateur connecté.
     * Si l'utilisateur n'est pas connecté, il est redirigé avec une erreur 401.
     *
     * @return void
     */
    public static function updateUser(): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location:/', 401);
        }
        BaseController::render('user/update', $user);
    }

    /**
     * Met à jour les informations de l'utilisateur connecté.
     *
     * Vérifie les données soumises (nom d'utilisateur, email, et mot de passe).
     * Valide le format de l'email et met à jour les informations dans la base de données.
     * Si des erreurs sont détectées, l'utilisateur est redirigé avec des messages d'erreur.
     * Si l'utilisateur n'est pas connecté, il est redirigé avec une erreur 401.
     *
     * @return void
     */
    public static function update(): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location:/', 401);
        }
        $username = !empty($_POST['username']) ? $_POST['username'] : $user['username'];
        $email = !empty($_POST['email']) ? $_POST['email'] : $user['email'];
        $password = !empty($_POST['password']) ? $_POST['password'] : $user['password'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["errors"]["email"] = "le format de l'adresse email n'est pas valide";
            header('Location:/profile/update');
            exit();
        }
        $userModel = new User();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $values = ["username" => $username,
            'password' => $hashedPassword === $user['password'] ? $password : $hashedPassword,
            "email" => $email];
        $userModel->update($user['id'], $values);
        header('Location:/profile');
    }
}