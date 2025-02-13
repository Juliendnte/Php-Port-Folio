<?php

namespace App\models;

class Skill extends Model
{
    protected string $table = 'skills';

    public function getAllSkills(): bool|array
    {
        return $this->findAll();
    }

    public function getSkillById($id): bool|array
    {
        return $this->findAllBy('id', $id);
    }

    public function addSkill(string $name): bool|array
    {
        return $this->create(['name' => $name]);
    }

    public function deleteSkill(int $id): bool|int
    {
        return $this->delete($id);
    }
}