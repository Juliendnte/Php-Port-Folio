<?php

namespace App\controllers;

use App\models\Project;

class ProjectController
{
    public static function addProject(): void
    {
        if (!AuthController::connected()) {
            header('Location: /');
        } else {
            BaseController::render('projects/addProject');
        }
    }


    public static function create(): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location: /');
            exit;
        }
        $_SESSION['errors'] = [];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $link = trim($_POST['link'] ?? NULL);
        $image = $_FILES['image'];

        if (empty($title)) {
            $_SESSION['errors']["title"] = "Le titre est obligatoire";
        }
        if (empty($description)) {
            $_SESSION['errors']["description"] = "La description est obligatoire";
        }

        if (isset($_SESSION["errors"]) && count($_SESSION["errors"]) > 0) {
            header('location:/projects/add', 400);
            exit;
        }

        $imagePath = '/project/default.png';
        if (!empty($image) && $image['error'] === UPLOAD_ERR_OK) {
            $temporaryPath = $image['tmp_name'];
            $originalName = $image['name'];
            $fileParts = pathinfo($originalName);
            $extension = strtolower($fileParts['extension']);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($extension, $allowedExtensions)) {
                $_SESSION['errors']['image'] = "Le format du fichier n'est pas supporté (jpg, jpeg, png, gif uniquement).";
                header('Location: /projects/add');
                exit;
            }

            $fileName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $fileParts['filename']);
            $imgName = 'project/' . $fileName . '-' . date('Y-m-d-H-i-s') . '.' . $extension;
            $rootPath = dirname(__DIR__, 2);
            $destinationDirectory = $rootPath . '/public/images/';
            $destinationPath = $destinationDirectory . $imgName;
            if (move_uploaded_file($temporaryPath, $destinationPath)) {
                $imagePath = $imgName;
            } else {
                $_SESSION['errors']['image'] = "Une erreur est survenue lors du téléchargement de l'image.";
                header('Location: /projects/add');
                exit;
            }
        }
        $projectModel = new Project();
        $projectModel->createProject($title, $description, $imagePath, $link, $user['id']);

        header('Location: /profile');
    }
}