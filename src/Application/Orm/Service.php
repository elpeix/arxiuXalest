<?php

namespace App\Application\Orm;

use \App\Application\Exception\InternalException;

class Service {

    private $sql;
    private $sqlSelect;
    private $pagination;
    protected $queryParams;
    protected $queryValues;
    protected $reflectionObject;
    protected StatementPrepare $statementPreparer;
    protected QueryExecuter $queryExecuter;

    public function __construct(string $model) {
        $this->reflectionObject = new ReflectionObject($model);
        $this->statementPreparer = new StatementPrepare($this->reflectionObject);
        $this->queryExecuter = new QueryExecuter();
        $this->clean();
    }

    protected function sqlAdd(string $sqlPiece) {
        $this->sql .= $sqlPiece;
    }

    protected function initSelect(string $sqlSelect) {
        $this->clean();
        $this->sqlSelect = $sqlSelect;
    }

    protected function getAlias(string $field="") {
        $level = 1;
        if (strlen($field) == 0) {
            return $this->reflectionObject->getObject()->getEntity() . $level;
        }
        $aliasField = $this->getAliasField($this->reflectionObject->getRelationFields(), $field, $level + 1);
        if ($aliasField == null) {
            throw new InternalException("Invalid field name [$field]", 1);
        }
        return $aliasField;
    }

    private function getAliasField($relationFields, $field, $level): ?string {
        foreach ($relationFields as $item) {
            if ($item[0] == $field) {
                return "$item[2]$level";
            }
            $aliasField =  $this->getAliasField($item[3], $field, $level + 1);
            if ($aliasField != null) {
                return $aliasField;
            }
        }
        return null;
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
        $sql = $this->statementPreparer->getSelectCount($this->sql);
        $result = $this->queryExecuter->executeQuery($sql, $this->queryParams);
        if (count($result) > 0) {
            return (int) $result[0]['count'];
        }
        return 0;
    }

    protected function execute(bool $withModel = false) {
        $sql = $this->statementPreparer->getSelect($this->sqlSelect, $this->sql);
        $queryValues = $this->queryValues;
        if (!empty($this->pagination["sql"])){
            $sql .= $this->pagination["sql"];
            $queryValues = array_merge($queryValues, $this->pagination["params"]);
        }
        $result = $this->queryExecuter->executeQuery($sql, $this->queryParams, $queryValues);
        if ($withModel) {
            $objectEnsembler = new ObjectEnsembler($this->reflectionObject);
            $list = [];
            foreach ($result as $rowData) {
                $row = $objectEnsembler->ensemble($rowData);
                $this->initFields($this->reflectionObject, $row);
                $this->setCollections($this->reflectionObject, $row);
                \array_push($list, $row);
            }
            return $list;
        }
        return $result;
    }

    protected function executeNativeQuery() {
        return $this->queryExecuter->executeQuery($this->sql, $this->queryParams, $this->queryValues);
    }

    private function initFields(ReflectionObject $reflectionObject, $row) {
        foreach($reflectionObject->getProperties() as $property) {
            if (!$property['collection'] && !$property['builtin']) {
                $propertyReflectionObject = new ReflectionObject($property['type']);
                if (!isset($row->{$property['name']})) {
                    $row->{$property['name']} = $propertyReflectionObject->createObject();
                }
                $this->initFields($propertyReflectionObject, $row->{$property['name']});
            }
        }
    }

    private function setCollections(ReflectionObject $reflectionObject, $row) {
        foreach ($reflectionObject->getProperties() as $property) {
            if ($property['collection'] && !$property['builtin']) {
                $service = new Service($property['type']);
                $service->initSelect('SELECT ');
                $service->sqlAdd(" WHERE " . $service->getAlias() . "." . $property['relation'] . " = :" . $property['relation']);
                $service->addQueryParam(':' . $property['relation'] , $row->attributes['id']['value']);
                $row->attributes[$property['name']]['value'] = $service->execute(true);
            } else if (!$property['builtin']){
                $value = $row->attributes[$property['name']]['value'];
                if (!isset($value) && $property['nullable']) {
                    continue;
                }
                $this->setCollections(new ReflectionObject($property['type']), $value);
            }
        }
    }

    protected function setOrderBy(array $params) {
        if (isset($params['orderBy'])) {
            $allowedOrderFields = $this->reflectionObject->getObject()->getAllowedOrderFields();
            $orderParams = explode(" ", $params['orderBy']);
            $orderIndex = array_search($orderParams[0], $allowedOrderFields);
            if ($orderIndex >= 0) {
                $result = ' ORDER BY ' . $this->getQueryField($allowedOrderFields[$orderIndex]);
                if (isset($orderParams[1]) && strtolower($orderParams[1]) == 'desc'){
                    $result .= ' DESC';
                }
                $this->sqlAdd($result);
            }
        }
    }

    protected function getQueryField(string $preQueryField) {
        $values = explode(".", $preQueryField);
        if (count($values) == 1) {
            return $this->getAlias() . "." . $values[0];
        } else if (count($values) == 2) {
            return $this->getAlias($values[0]) . "." . $values[1];
        }
        throw new \Exception("Invalid levels in [$preQueryField]");
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

    public function insert($object): int {
        $statementPrepared = $this->statementPreparer->getInsert($object);
        return $this->queryExecuter->insert($statementPrepared['sql'], $statementPrepared['queryParams']);
    }

    public function update(int $id, $object) {
        $statementPrepared = $this->statementPreparer->getUpdate($id, $object);
        $this->queryExecuter->update($statementPrepared['sql'], $statementPrepared['queryParams']);
        return $object;
    }

    public function delete(int $id) {
        $statementPrepared = $this->statementPreparer->getDelete($id);
        $this->queryExecuter->delete($statementPrepared['sql'], $statementPrepared['queryParams']);
    }

    public function createObject() {
        return $this->reflectionObject->createObject();
    }

}