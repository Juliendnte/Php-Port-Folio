<?php

namespace App\models;

class Project extends Model
{
    protected string $table = 'projects';

    public function createProject($title, $description, $image, $link, $id_user): void
    {
        $this->create([
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
}