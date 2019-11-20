<?php

namespace App\Application\Orm;

use App\Application\Orm\Database;
use App\Application\Exception\BadRequestApiException;
use \PDO;
use \ReflectionClass;
use \ReflectionProperty;

abstract class Service {

    private $sql;
    private $sqlSelect;
    private $pagination;
    protected $queryParams;
    protected $queryValues;
    protected $reflectionObject;
    protected $dbConnection;

    public function __construct(string $model) {
        $db = new Database();
        $this->dbConnection = $db->connect();
        $this->reflectionObject = new ReflectionObject($model);
        $this->clean();
    }

    protected function sqlAdd(string $sqlPiece) {
        $this->sql .= $sqlPiece;
    }

    protected function initSelect(string $sqlSelect) {
        $this->sqlSelect = $sqlSelect;
    }

    protected function clean() {
        $this->sql = '';
        $this->sqlSelect = '';
        $this->queryParams = array();
        $this->queryValues = array();
        $this->pagination = array();
    }

    protected function addQueryParam(string $name, $value) {
        $this->queryParams[$name] = $value;
    }

    protected function addQueryValue(string $name, $value) {
        $this->queryValues[$name] = $value;
    }

    protected function getQueryResults() {
        return $this->execute(true);
    }

    protected function getCount(){
        try {
            $sql = $this->createStatement('SELECT count(1) as count ');
            $stmt = $this->dbConnection->prepare($sql);
            $this->setQueryParams($stmt, $this->queryParams, []);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if (count($result) > 0) {
                return (int) $result[0]['count'];
            }
            return -1;
        } catch (Exception $e) {
            //throw new Exception("Error Processing Request", 1);
        }
    }

    protected function execute(bool $withModel = false) {
        try {
            $sql = $this->createStatement($this->sqlSelect);
            $queryValues = $this->queryValues;
            if (!empty($this->pagination["sql"])){
                $sql .= $this->pagination["sql"];
                $queryValues = array_merge($queryValues, $this->pagination["params"]);
            }
            $stmt = $this->dbConnection->prepare($sql);
            $this->setQueryParams($stmt, $this->queryParams, $queryValues);
            $stmt->execute();
            if ($withModel) {
                return $stmt->fetchAll(PDO::FETCH_CLASS, $this->reflectionObject->getModel());
            }
            return $stmt->fetchAll();
        } catch (Exception $e) {
            //throw new Exception("Error Processing Request", 1);
        }
    }

    private function createStatement(string $select): string {
        $statementPrepare = new StatementPrepare($this->reflectionObject);
        return $select . $statementPrepare->getFrom() . $this->sql;
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

    protected function setOrderBy(array $params) {
        if (isset($params['orderBy'])) {
            $allowedOrderFields = $this->reflectionObject->getObject()->getAllowedOrderFields();
            $orderParams = explode(" ", $params['orderBy']);
            $orderIndex = array_search($orderParams[0], $allowedOrderFields);
            if ($orderIndex >= 0) {
                $result = ' ORDER BY ' . $allowedOrderFields[$orderIndex];
                if (isset($orderParams[1]) && strtolower($orderParams[1]) == 'desc'){
                    $result .= ' DESC';
                }
                $this->sqlAdd($result);
            }
        }
    }

    protected function setPagination(array $params) {
        $perPage = MAX_PER_PAGE;
        if (isset($params['perPage']) && intVal($params['perPage'] > 0) && intVal($params['perPage']) <= $perPage) {
            $perPage = intVal($params['perPage']);
        }
        $result = " LIMIT :limit";
        $this->pagination['params'][':limit'] = $perPage;
        if (isset($params['page']) && intVal($params['page'] > 1)) {
            $result .= " OFFSET :offset";
            $offset = ((intVal($params['page']) - 1) * $perPage);
            $this->pagination['params'][':offset'] = $offset;
        }
        $this->pagination["sql"] = $result;
    }

    protected function insert($object): int {
        $statementPrepare = new StatementPrepare($this->reflectionObject);
        $statementPrepared = $statementPrepare->getInsert($object);
        try {
            $this->dbConnection->beginTransaction();
            $stmt= $this->dbConnection->prepare($statementPrepared['sql']);
            $stmt->execute($statementPrepared['queryParams']);
            $lastInsertId = $this->dbConnection->lastInsertId();
            $this->dbConnection->commit();
        } catch (Exception $e) {
            $this->dbConnection->rollback();
            throw new Exception("Error Processing Request", 1);
        }
        return $lastInsertId;
    }

    protected function update(int $id, $object) {
        $statementPrepare = new StatementPrepare($this->reflectionObject);
        $statementPrepared = $statementPrepare->getUpdate($id, $object);
        try {
            $this->dbConnection->beginTransaction();
            $stmt= $this->dbConnection->prepare($statementPrepared['sql']);
            $stmt->execute($statementPrepared['queryParams']);
            $this->dbConnection->commit();
        } catch (Exception $e) {
            $this->dbConnection->rollback();
            throw new Exception("Error Processing Request", 1);
        }
        return $object;
    }

    protected function delete(int $id) {
        $statementPrepare = new StatementPrepare($this->reflectionObject);
        $statementPrepared = $statementPrepare->getDelete($id);
        try {
            $this->dbConnection->beginTransaction();
            $stmt= $this->dbConnection->prepare($statementPrepared['sql']);
            $stmt->execute($statementPrepared['queryParams']);
            $this->dbConnection->commit();
        } catch (Exception $e) {
            $this->dbConnection->rollback();
            throw new Exception("Error Processing Request", 1);
        }
    }

    public function createObject() {
        return $this->reflectionObject->createObject();
    }

}