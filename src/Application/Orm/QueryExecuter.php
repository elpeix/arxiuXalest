<?php

namespace App\Application\Orm;

use App\Application\Orm\Database;
use \PDO;

class QueryExecuter {

    private $dbConnection;

    public function __construct() {
        $db = new Database();
        $this->dbConnection = $db->connect();
    }

    public function executeQuery(string $sql, array $queryParams = [], array $queryValues = []) {
        try {
            $stmt = $this->dbConnection->prepare($sql);
            $this->setQueryParams($stmt, $queryParams, $queryValues);
            $stmt->execute();
            return $stmt->fetchAll(); 
        } catch (\Exception $e) {
            $msg = [
                'SQL' => $sql,
                'QUERY_PARAMS' => $queryParams,
                'QUERY_VALUES' => $queryValues,
                'EXCEPTION' => $e
            ];
            throw new \Exception("Error processing select", 1);
        }
    }

    private function setQueryParams($stmt, array $queryParams, array $queryValues) {
        foreach ((array) $queryParams as $key => $value) {
            switch (gettype($value)) {
                case 'boolean':
                    $stmt->bindValue($key, (bool) $value, PDO::PARAM_BOOL);
                    break;
                case 'integer':
                    $stmt->bindValue($key, (int) $value, PDO::PARAM_INT);
                    break;
                case 'double':
                case 'string':
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                    break;
            }
        }
        foreach ((array) $queryValues as $key => $value) {
            switch (gettype($value)) {
                case 'boolean':
                    $stmt->bindValue($key, (bool) $value, PDO::PARAM_BOOL);
                    break;
                case 'integer':
                    $stmt->bindValue($key, (int) $value, PDO::PARAM_INT);
                    break;
                case 'double':
                case 'string':
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                    break;
            }
        }
    }


    public function insert(string $sql, array $queryParams) {
        $lastInsertId = 0;
        try {
            $this->dbConnection->beginTransaction();
            $this->execute($sql, $queryParams);
            $lastInsertId = $this->dbConnection->lastInsertId();
            $this->dbConnection->commit();
        } catch (\Exception $e) {
            $this->dbConnection->rollback();
            throw new \Exception("Error processing insert", 1, $e);
        }
        return $lastInsertId;
    }

    public function update(string $sql, array $queryParams) {
        try {
            $this->dbConnection->beginTransaction();
            $this->execute($sql, $queryParams);
            $this->dbConnection->commit();
        } catch (\Exception $e) {
            $this->dbConnection->rollback();
            throw new \Exception("Error processing update", 1);
        }
    }

    public function delete(string $sql, array $queryParams) {
        try {
            $this->dbConnection->beginTransaction();
            $this->execute($sql, $queryParams);
            $this->dbConnection->commit();
        } catch (\Exception $e) {
            $this->dbConnection->rollback();
            throw new \Exception("Error processing delete", 1);
        }
    }

    public function execute(string $sql, array $queryParams) {
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute($queryParams);
    }

}
