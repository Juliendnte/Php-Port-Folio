<?php

namespace App\models;

class User extends Model
{
    protected string $table = 'users';

    public function findAllUsers(): bool|array
    {
        return $this->findAll();
    }
    public function findByEmail(string $email) : array | bool
    {
        return $this->findOneBy('email', $email);
    }
}