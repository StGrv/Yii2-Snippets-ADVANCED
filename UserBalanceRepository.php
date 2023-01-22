<?php

namespace app\modules\bookshop\repositories;

use app\modules\bookshop\interfaces\UserBalanceRepoInterface;

class UserBalanceRepository implements UserBalanceRepoInterface
{
    /**
     * @param int $userId
     * @param float $value
     * @return bool
     */
    public function addToBalance(int $userId, float $value): bool
    {
        $update = bsDb()->createCommand("
            UPDATE 
                user u
            SET
                u.balance = u.balance + :value
            WHERE
                u.id = :id
        ", [
            'value' => $value,
            'id' => $userId
        ])
            ->execute();

        if (!$update) {
            return false;
        }

        return true;
    }

    /**
     * @param int $userId
     * @return float
     */
    public function getBalance(int $userId): float
    {
        $result = bsDb()->createCommand("
            SELECT
                u.balance
            FROM 
                user u
            WHERE
                u.id = :id
        ", [
            'id' => $userId
        ])
            ->queryColumn();

        return $result[0];
    }

    /**
     * @param int $userId
     * @param float $value
     * @return bool
     */
    public function deductFromBalance(int $userId, float $value): bool
    {
        $update = bsDb()->createCommand("
            UPDATE 
                user u
            SET
                u.balance = u.balance - :value
            WHERE
                u.id = :userId
        ", [
            'value' => $value,
            'userId' => $userId
        ])
            ->execute();

        if (!$update) {
            return false;
        }

        return true;
    }
}