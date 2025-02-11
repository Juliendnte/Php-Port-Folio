<?php

namespace App\models;

use App\models\Model;

class Roles extends Model
{
    protected string $table = 'roles';


    public function getIdRole(string $role): bool|array
    {
        return $this->findOneBy('role', $role);
    }
}