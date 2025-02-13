<?php

namespace App\models;

class User extends Model
{
    protected string $table = 'users';

    public function findAllUsers(): bool|array
    {
        return $this->findAll();
    }

    public function findByEmail(string $email): array|bool
    {
        return $this->findOneBy('email', $email);
    }

    public function findByRememberToken(string $token): array|bool
    {
        return $this->findOneBy('remember_token', $token);
    }

    public function updateRememberToken(int $id, string $token): bool|int
    {
        return $this->update($id, ['remember_token' => $token]);

    }

    public function clearRememberToken(int $id): bool|int
    {
        return $this->update($id, ['remember_token' => NULL]);
    }

}