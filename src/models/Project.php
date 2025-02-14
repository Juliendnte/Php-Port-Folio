<?php

namespace App\models;

class Project extends Model
{
    protected string $table = 'projects';


    public function getAllProjects(): bool|array
    {
        return $this->findAll();
    }

    public function getProjectById($id): bool|array
    {
        return $this->findOneBy('id', $id);
    }

    public function createProject($title, $description, $image, $link, $id_user): bool|int
    {
        return $this->create([
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'link' => $link,
            'user_id' => $id_user,
        ]);
    }

    public function findAllProjectsByUser($id): array
    {
        return $this->findAllBy('user_id', $id);
    }

    public function updateProject(
        $id,
        $title,
        $description,
        $image,
        $link,
    ): bool|int
    {
        return $this->update($id, [
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'link' => $link,
        ]);

    }
}