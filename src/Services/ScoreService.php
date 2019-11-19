<?php
namespace App\Services;

use App\Models\Score;
use App\Application\Exception\NotFoundApiException;

class ScoreService extends BasicService {

    private $entity;

    public function __construct() {
        parent::__construct('App\Models\Score');
        $this->entity = $this->reflectionObject->getObject()->entity();
    }

    public function getList(array $params) {
        $this->initSelect($this->getBaseSelect());
        $this->setJoins();
        $this->setFilter($params);
        $this->setOrderBy($params);
        $this->setPagination($params);
        $resultList = $this->execute();
        $items = array();
        foreach ($resultList as $obj) {
            \array_push($items, Score::createObject($obj));
        }
        return [
            'count' => $this->getCount(),
            'items' => $items
        ];
    }

    private function setFilter(array $params) {
        if (!isset($params['filter'])){
            return;
        }
        $filter = array();
        $queryParams = array();
		foreach (preg_split('/&/', $params['filter']) as $key => $value) {
            $filterItem = preg_split('/=/', $value);
            if ($this->isValid($filterItem[0]) && \trim($filterItem[1] != "")) {
                $itemWildcard = str_replace('.', '_', $filterItem[0]);
                $arrParam = preg_split('/\./', $filterItem[0]);
                if (count($arrParam) == 1){
                    array_push($filter, "$this->entity.$filterItem[0]=:$filterItem[0]");
                    $this->addQueryParam(":$itemWildcard", $filterItem[1]);
                } else {
                    array_push($filter, "$filterItem[0] LIKE :$itemWildcard");
                    $this->addQueryParam(":$itemWildcard", "%$filterItem[1]%");
                }
            }
        }
        if ($filter > 0) {
            $this->sqlAdd(' WHERE ' . implode(' AND ', $filter));
        }
    }
    
    private function isValid(string $property): bool {
        return $this->reflectionObject->getObject()->validateFilterField($property);
    }

    public function getItem(int $id) {
        $this->clean();
        $this->initSelect($this->getBaseSelect());
        $this->setJoins();
        $this->sqlAdd(" WHERE $this->entity.id = :id");
        $this->addQueryParam(':id', $id);
        $resultList = $this->execute();
        if (count($resultList) == 0) {
            throw new NotFoundApiException();
        }
        return Score::createObject($resultList[0]);
    }

    protected function getBaseSelect():string {
        $select = "SELECT $this->entity.id as _id, $this->entity.name as _name, $this->entity.century as _century";
        foreach (Score::getRelationFields() as $item) {
            $select .= ", $item[0].id as $item[1]_id, $item[0].name as $item[1]_name";
        }
        return $select;
    }

    private function setJoins() {
        foreach (Score::getRelationFields() as $item) {
            $this->sqlAdd(" LEFT JOIN $item[0] ON $this->entity.$item[1]Id = $item[0].id");
        }
    }

    public function create(array $body) {
        $partituresStatement = new ScoreStatement($this->entity, $body);
        try {
            $this->dbConnection->beginTransaction();
            $stmt= $this->dbConnection->prepare($partituresStatement->getInsertStatement());
            $stmt->execute($partituresStatement->getQueryParams());
            $lastInsertId = $this->dbConnection->lastInsertId();
            $this->dbConnection->commit();
        } catch (\PDOException $e) {
            $this->dbConnection->rollback();
            //throw new Exception("Error Processing Request", 1);
        }
        return $this->getItem($lastInsertId);
    }

    public function edit(int $id, array $body) {
        $obj = $this->getItem($id);
        $partituresStatement = new ScoreStatement($this->entity, $body);
        $queryParams = $partituresStatement->getQueryParams();
        $queryParams[':id'] = $id;
        try {
            $this->dbConnection->beginTransaction();
            $stmt= $this->dbConnection->prepare($partituresStatement->getUpdateStatement());
            $stmt->execute($queryParams);
            $this->dbConnection->commit();
        } catch (\PDOException $e) {
            $this->dbConnection->rollback();
            //throw new Exception("Error Processing Request", 1);
        }
        return $this->getItem($id);
    }

}