<?php

namespace App\controllers;

use App\models\User;
use Exception;
use Random\RandomException;

/**
 * Contrôleur d'authentification pour gérer les actions liées à l'authentification des utilisateurs.
 */
class AuthController
{

    /**
     * Affiche la page de connexion si l'utilisateur n'est pas connecté.
     *
     * Si déjà connecté, redirige vers la page d'accueil.
     *
     * @return void
     */
    public static function login(): void
    {
        if (self::connected()) {
            header("Location:/");
        }
        BaseController::render('auth/login');
    }

    /**
     * Authentifie un utilisateur à l'aide des identifiants soumis.
     *
     * Vérifie, nettoie et valide les données envoyées via le formulaire pour connecter l'utilisateur.
     * Si les informations sont incorrectes, redirige vers la page de connexion.
     * En cas de succès, l'utilisateur est redirigé vers la page d'accueil.
     *
     * @throws RandomException En cas d'erreur lors de la génération du token.
     * @return void
     */
    public static function authenticate(): void
    {
        if (self::connected()) {
            header("Location:/");
        }
        $password = $_POST["password"];
        $email = $_POST["email"];
        $rememberMe = isset($_POST["remember"]) && $_POST["remember"] === "on";


        if (empty($email)) {
            $_SESSION["errors"]["email"] = "Il faut saisir une adresse email";
        }

        if (empty($password)) {
            $_SESSION["errors"]["password"] = "Il faut saisir un password";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["errors"]["email"] = "Votre email n'est pas valide";
        }

        if (isset($_SESSION["errors"]) && count($_SESSION["errors"]) > 0) {
            $_SESSION["log_email"] = $email;
            $_SESSION["log_password"] = $password;
            header('location:login', 400);
            return;
        }

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (empty($user) || !password_verify($password, $user['password'])) {
            $_SESSION["log_email"] = $email;
            $_SESSION["log_password"] = $password;
            $_SESSION["errors"]["identifiant"] = "Données incorrects";
            header('location:login');
            return;
        }

        $_SESSION["email"] = $email;
        if ($rememberMe) {
            $token = bin2hex(random_bytes(32));
            $expires = time() + 30 * 24 * 60 * 60;

            $userModel->updateRememberToken($user['id'], $token);

            setcookie('remember_me', $token, [
                'expires' => $expires,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
        }
        unset($_SESSION["log_email"]);
        unset($_SESSION["log_password"]);

        header("location:/");
    }

    /**
     * Vérifie le cookie "remember_me" pour connecter automatiquement un utilisateur.
     *
     * Si un token valide est trouvé, l'utilisateur sera connecté via la session.
     *
     * @return void
     */
    public static function checkRememberMe(): void
    {
        if (isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            $userModel = new User();
            $user = $userModel->findByRememberToken($token);

            if ($user) {
                $_SESSION["email"] = $user['email'];
            }
        }
    }

    /**
     * Affiche la page d'enregistrement pour un nouvel utilisateur.
     *
     * Si l'utilisateur est déjà connecté, redirige vers la page d'accueil.
     *
     * @return void
     */
    public static function register(): void
    {
        if (self::connected()) {
            header("Location:/");
        }
        BaseController::render('auth/register');
    }

    /**
     * Enregistre un nouvel utilisateur dans le système.
     *
     * Valide les informations envoyées via le formulaire (email, mot de passe, etc.).
     * Si les informations sont invalides ou qu'une erreur survient, redirige vers la page de registre avec des erreurs.
     *
     * En cas de succès, redirige l'utilisateur vers la page d'accueil.
     *
     * @return void
     */
    public static function record(): void
    {
        if (self::connected()) {
            header("Location:/");
        }

        $email = $_POST["email"];
        $password = $_POST["password"];
        $usernane = $_POST["username"];

        if (empty($email)) {
            $_SESSION["errors"]["email"] = "Il faut saisir une adresse email";
        }

        if (empty($password)) {
            $_SESSION["errors"]["password"] = "Il faut saisir un password";
        }

        if (!($password === $_POST["confirm_password"])) {
            $_SESSION["errors"]["confirm_password"] = "Les mots de passes doivent être identiquement saisis";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["errors"]["email"] = "le format de l'adresse email n'est pas valide";
        }

        if (isset($_SESSION["errors"]) && count($_SESSION["errors"]) > 0) {
            $_SESSION["log_username"] = $usernane;
            $_SESSION["log_email"] = $email;
            $_SESSION["log_password"] = $password;
            $_SESSION["log_confirm_password"] = $_POST["confirm_password"];
            header('Location:register', 400);
            return;
        }

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $userModel = new User();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $values = ["username" => $usernane,
            'password' => $hashedPassword,
            "email" => $email,];
        if ($userModel->create($values) === false) {
            $_SESSION["log_username"] = $usernane;
            $_SESSION["log_email"] = $email;
            $_SESSION["log_password"] = $password;
            $_SESSION["log_confirm_password"] = $_POST["confirm_password"];
            $_SESSION["errors"]["BDD"] = $e->getMessage();
            header('Location:register', 400);
            return;
        }

        unset($_SESSION["log_email"]);
        unset($_SESSION["log_password"]);
        unset($_SESSION["log_confirm_password"]);
        unset($_SESSION["log_username"]);
        $_SESSION["email"] = $email;
        header('Location:/', 200);
    }

    /**
     * Déconnecte l'utilisateur actuellement connecté.
     *
     * Réinitialise la session, nettoie les cookies, et supprime le token "remember_me" de la base de données
     * pour s'assurer qu'aucune donnée d'authentification persistante ne reste.
     *
     * Redirige vers la page d'accueil après la déconnexion.
     *
     * @return void
     */

    public static function logout(): void
    {
        if (isset($_SESSION["email"])) {
            $userModel = new User();
            $email = filter_var($_SESSION["email"], FILTER_SANITIZE_EMAIL);
            $user = $userModel->findByEmail($email);

            if ($user) {
                $userModel->clearRememberToken($user['id']);
            }
        }

        session_unset();
        session_destroy();
        setcookie(session_name(), "", strtotime("-1 day"));
        setcookie('remember_me', '', time() - 3600, '/', '', true, true);

        header('Location:/');
    }

    /**
     * Vérifie si un utilisateur est connecté.
     *
     * Cette méthode vérifie si une session utilisateur est active et si un email valide est associé à cette session.
     * Si un utilisateur est trouvé avec cet email dans la base de données, retourne ses informations.
     *
     * @return bool|array Retourne un tableau contenant les informations de l'utilisateur connecté, ou `false` si non connecté.
     */
    public static function connected(): bool|array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION["email"])) {
            $email = $_SESSION["email"];
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!empty($email)) {
                $userModel = new User();
                return $userModel->findByEmail($email);
            }
        }
        return false;
    }
}