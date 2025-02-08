<?php

namespace App\controllers;

use App\models\User;
use Exception;

class AuthController
{

    public static function login(): void
    {
        BaseController::render('auth/login');
    }

    public static function authenticate(): void
    {
        $password = $_POST["password"];
        $email = $_POST["email"];

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
            header('location:login', 400);
            return;
        }

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (empty($user) || !password_verify($password, $user['password'])) {
            $_SESSION["errors"]["identifiant"] = "Données incorrects";
            header('location:login');
            return;
        }

        $_SESSION["email"] = $email;
        $destination = "/";
        if (!empty($_SESSION["destination"])) {
            $destination = $_SESSION["destination"];
            unset($_SESSION["destination"]);
        }
        header("location:$destination");
    }

    public static function register(): void
    {
        BaseController::render('auth/register');
    }

    public static function record(): void
    {
        if (self::connected()) {
            header("Location:");
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
            $_SESSION["errors"]["BDD"] = $e->getMessage();
            header('Location:register', 400);
            return;
        }

        $_SESSION["email"] = $email;
        header('Location:/', 200);
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
        setcookie(session_name(), "", strtotime("-1 day"));


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