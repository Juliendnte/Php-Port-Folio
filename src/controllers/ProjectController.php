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
            $maxFileSize = 2 * 1024 * 1024;
            if ($image['size'] > $maxFileSize) {
                $_SESSION['errors']['image'] = "L'image est trop volumineuse. Taille maximale autorisée : 2 Mo.";
                header('Location: /projects/add');
                exit;
            }

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

    public static function deleteProject(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location: /');
            exit;
        }
        $projectModel = new Project();
        $projectModel->delete($id);
        header('Location: /profile');
    }

    public static function updateProject(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location: /');
            exit;
        }

        $projectModel = new Project();
        $project = $projectModel->findOneBy('id', $id);
        BaseController::render('projects/updateProject', ['project' => $project]);
    }

    public static function update(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location: /');
            exit;
        }

        $projectModel = new Project();
        $project = $projectModel->findOneBy('id', $id);

        // Chargement des données existantes ou des nouvelles si transmises
        $title = empty($_POST['title']) ? $project['title'] : $_POST['title'];
        $description = empty($_POST['description']) ? $project['description'] : $_POST['description'];
        $link = empty($_POST['link']) ? $project['link'] : $_POST['link'];
        $image = $_FILES['image'];
        $imagePath = $project['image'];

        if (!empty($image) && $image['error'] === UPLOAD_ERR_OK && $imagePath !== 'project/default.png') {
            $maxFileSize = 2 * 1024 * 1024; // Taille maximale de 2 Mo
            if ($image['size'] > $maxFileSize) {
                $_SESSION['errors']['image'] = "L'image est trop volumineuse. Taille maximale autorisée : 2 Mo.";
                header('Location: /projects/update/' . $id );
                exit;
            }


            $rootPath = dirname(__DIR__, 2);
            $oldImagePath = $rootPath . '/public/images/' . $project['image'];

            if (file_exists($oldImagePath) && $project['image'] !== 'project/default.png') {
                unlink($oldImagePath);
            }

            $temporaryPath = $image['tmp_name'];
            $originalName = $image['name'];
            $fileParts = pathinfo($originalName);
            $extension = strtolower($fileParts['extension']);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($extension, $allowedExtensions)) {
                $_SESSION['errors']['image'] = "Le format du fichier n'est pas supporté (jpg, jpeg, png, gif uniquement).";
                header('Location: /projects/edit/' . $id);
                exit;
            }

            $fileName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $fileParts['filename']);
            $imgName = 'project/' . $fileName . '-' . date('Y-m-d-H-i-s') . '.' . $extension;
            $destinationDirectory = $rootPath . '/public/images/';
            $destinationPath = $destinationDirectory . $imgName;

            if (move_uploaded_file($temporaryPath, $destinationPath)) {
                $imagePath = $imgName;
            } else {
                $_SESSION['errors']['image'] = "Une erreur est survenue lors du téléchargement de l'image.";
                header('Location: /projects/edit/' . $id);
                exit;
            }
        }

        $projectModel->updateProject($id, $title, $description, $imagePath, $link);

        header('Location: /profile');
    }
}