<?php

namespace app\src\Common\Helpers;

use app\src\Common\Exceptions\SQLExceptions\ConflictException;
use app\src\Common\Exceptions\SQLExceptions\ConnectionDoneException;
use app\src\Common\Exceptions\SQLExceptions\NoRowsException;
use app\src\Common\Exceptions\SQLExceptions\SQLException;
use app\src\Common\Exceptions\SQLExceptions\TransactionRollbackException;
use PDO;

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
    public static function exec(PDO $pdo, callable $fnCallback,  string $isolation_level = "READ COMMITTED"): mixed
    {
        $pdo->exec("SET TRANSACTION ISOLATION LEVEL " . $isolation_level);

        $pdo->beginTransaction();

        try {
            $result = $fnCallback($pdo);
            if ($result === false) {
                $pdo->rollBack();
                return false;
            }
            $pdo->commit();

            return $result;
        } catch (SQLException $e) {
            $pdo->rollBack();
 
             throw $e;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() === "08003") {
                throw new ConnectionDoneException('Database connection has been closed: ' . $e->getMessage());
            };
            
            throw new TransactionRollbackException("Transaction failed: " . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
