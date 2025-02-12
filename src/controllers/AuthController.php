<?php

namespace App\controllers;

use App\models\User;
use Exception;
use Random\RandomException;

class AuthController
{

    public static function login(): void
    {
        if (self::connected()){
            header("Location:/");
        }
        BaseController::render('auth/login');
    }

    /**
     * @throws RandomException
     */
    public static function authenticate(): void
    {
        if (self::connected()){
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

    public static function checkRememberMe(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (self::connected()) {
            return;
        }

        if (isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            $userModel = new User();
            $user = $userModel->findByRememberToken($token);

            if ($user) {
                $_SESSION["email"] = $user['email'];
            }
        }
    }

    public static function register(): void
    {
        if (self::connected()){
            header("Location:/");
        }
        BaseController::render('auth/register');
    }

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
            $_SESSION["log_email"] = $email;
            $_SESSION["log_password"] = $password;
            header('Location:register', 400);
            return;
        }

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        try {
            $userModel = new User();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $values = ["username" => $usernane,
                'password' => $hashedPassword,
                "email" => $email,];
            $userModel->create($values);

        } catch (Exception $e) {
            $_SESSION["log_email"] = $email;
            $_SESSION["log_password"] = $password;
            $_SESSION["errors"]["BDD"] = $e->getMessage();
            header('Location:register', 400);
            return;
        }
        unset($_SESSION["log_email"]);
        unset($_SESSION["log_password"]);
        $_SESSION["email"] = $email;
        header('Location:/', 200);
    }

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