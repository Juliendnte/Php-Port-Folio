<?php

namespace App\controllers;

use App\models\Project;
use App\models\User;

class HomeController
{
    public function index(): void
    {
        try{
            $projectModel = new Project();
            $project = $projectModel->getAllProjects();
            $userModel = new User();
            $user = $userModel->findAllUsers();
            BaseController::render('home', [
                'users' => $user,
                'projects' => $project,
            ]);
        }Catch(\Exception $e){
            ErrorController::error500();
        }
    }

}