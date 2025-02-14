<?php

namespace App\controllers;

use App\models\Project;

/**
 * Contrôleur des projets.
 *
 * Gère les actions associées à la gestion des projets (création, suppression, etc.).
 */
class ProjectController
{
    /**
     * Affiche la vue d'ajout d'un nouveau projet.
     *
     * Si l'utilisateur n'est pas connecté, il est redirigé vers la page d'accueil.
     *
     * @return void
     */
    public static function addProject(): void
    {
        if (!AuthController::connected()) {
            header('Location: /');
        } else {
            BaseController::render('projects/addProject');
        }
    }


    /**
     * Traite la création d'un nouveau projet soumis via un formulaire.
     *
     * Valide et nettoie les données saisies, gère le téléchargement d'image, et sauvegarde les informations
     * du projet dans la base de données. En cas d'erreur, redirige l'utilisateur avec des messages appropriés.
     *
     * @return void
     */
    public static function create(): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location: /');
            exit;
        }
        $title = $_POST['title'];
        $description = $_POST['description'];
        $link = trim($_POST['link'] ?? NULL);
        $image = $_FILES['image'];
        $_SESSION['project_title'] = $title;
        $_SESSION['project_description'] = $description;
        $_SESSION['project_link'] = $link;

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
        if (empty($projectModel->createProject($title, $description, $imagePath, $link, $user['id']))) {
            $_SESSION['errors']['BDD'] = 'une erreur est survenue';
            header('Location: /projects/add');
        } else {
            unset($_SESSION['project_title']);
            unset($_SESSION['project_description']);
            unset($_SESSION['project_link']);
            header('Location: /profile');
        }
    }

    /**
     * Supprime un projet existant.
     *
     * Vérifie l'autorisation de l'utilisateur connecté avant de procéder à la suppression d'un projet par son ID.
     * Si une erreur survient ou que l'utilisateur n'est pas autorisé, l'utilisateur est redirigé vers une page d'erreur.
     *
     * @param int $id L'identifiant du projet à supprimer.
     *
     * @return void
     */
    public static function deleteProject(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location: /');
            exit;
        }

        $projectModel = new Project();
        $project = $projectModel->findOneBy('id', $id);
        if ($project['user_id'] !== $user['id']) {
            header('Location: /error/404');
        }
        if (empty($project)) {
            $_SESSION['errors']['id_del_project'] = 'le projet n\'existe pas';
        }

        if (empty($projectModel->delete($id))) {
            header('Location: /error/500');
            exit();
        }

        header('Location: /profile');
    }


    /**
     * Affiche la vue de mise à jour d'un projet existant.
     *
     * Vérifie si l'utilisateur est connecté et autorisé à modifier le projet (basé sur l'ID du projet et l'utilisateur connecté).
     * Si l'utilisateur n'est pas connecté ou n'a pas les autorisations nécessaires, redirige vers une page d'erreur.
     *
     * @param int $id L'identifiant unique du projet à mettre à jour.
     *
     * @return void
     */
    public static function updateProject(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location: /');
            exit;
        }

        $projectModel = new Project();
        $project = $projectModel->findOneBy('id', $id);
        if ( $project['user_id'] !== $user['id'] || empty($project)) {
            header('Location: /error/404');
        }
        BaseController::render('projects/updateProject', ['project' => $project]);
    }

    /**
     * Met à jour un projet existant avec les nouvelles données fournies par un formulaire.
     *
     * Vérifie l'authentification et les autorisations de l'utilisateur connecté avant de procéder à la mise à jour.
     * Valide et traite les nouvelles données (titre, description, lien, image).
     * Si une image est remplacée, l'ancienne image associée au projet est supprimée du serveur.
     *
     * En cas d'erreur de validation, l'utilisateur est redirigé vers la page d'édition avec des messages d'erreur appropriés.
     *
     * @param int $id L'identifiant unique du projet à mettre à jour.
     *
     * @return void
     */
    public static function update(int $id): void
    {
        $user = AuthController::connected();
        if (empty($user)) {
            header('Location: /');
            exit;
        }

        $projectModel = new Project();
        $project = $projectModel->findOneBy('id', $id);
        if ($project['user_id'] !== $user['id'] || empty($project)) {
            header('Location: /error/404');
        }

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
                header('Location: /projects/update/' . $id);
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