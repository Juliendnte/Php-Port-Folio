<?php

namespace App\models;


class Users_skills extends Model
{

    protected string $table = 'users_skills';

    public function getAllUsersSkillsByUserId($userId): bool|array
    {
        return $this->findAllBy('user_id', $userId);
    }

    public function deleteUserSkill(int $userId, int $skillId): false|int
    {
        $sql = <<<sql
        DELETE FROM $this->table WHERE user_id = :userId AND skill_id = :skillId;
        sql;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":userId", $userId, \PDO::PARAM_INT);
            $stmt->bindParam(":skillId", $skillId, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function addUserSkill(int $userId, int $skillId, string $level): bool|int
    {
        return $this->create([
            'user_id' => $userId,
            'skill_id' => $skillId,
            'level' => $level,
        ]);
    }

    public function updateUserSkill(int $userId, int $skillId, string $level): bool|int
    {
        $sql = <<<sql
        UPDATE $this->table SET level = :level WHERE user_id = :userId AND skill_id = :skillId;
        sql;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":level", $level);
            $stmt->bindParam(":userId", $userId, \PDO::PARAM_INT);
            $stmt->bindParam(":skillId", $skillId, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Exception $e) {
            return false;
        }
    }
}