<?php

namespace app\src\Common\Helpers;

class DatabaseTransaction
{
    /**
     * Execute a transaction with a callback function.
     *
     * @param PDO $pdo The PDO instance to use for the transaction.
     * @param callable $fnCallback The callback function to execute within the transaction.
     * The callback must return a boolean value.
     * 
     * @return bool Returns true if the transaction was successful, false otherwise.
     * @throws Exception If an error occurs during the transaction.
     */
    public static function exec(\PDO $pdo, callable $fnCallback): bool
    {
        $pdo->exec("SET TRANSACTION ISOLATION LEVEL READ COMMITED");

        $pdo->beginTransaction();

        try {
            $result = $fnCallback($pdo);
            if ($result === false) {
                $pdo->rollBack();
                return false;
            }
            $pdo->commit();
            return true;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            throw new \Exception("Transaction failed: " . $e->getMessage(), 0, $e);
        }
    }
}
