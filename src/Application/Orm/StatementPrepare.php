<?php

namespace App\Application\Orm;

class StatementPrepare {

    private ReflectionObject $reflectionObject;

    public function __construct(ReflectionObject $reflectionObject) {
        $this->reflectionObject = $reflectionObject;
    }

    public function getSelectCount(string $sql = ""): string {
        return 'SELECT count(1) as count ' . $this->getFrom() . $sql;
    }

    public function getSelect(string $select, string $sql = ""): string {
        return $select  . $this->getFields() . $this->getFrom() . $sql;
    }

    public function getFields(): string {
        return $this->_getFields($this->reflectionObject);
    }    
    
    private function _getFields($reflectionObject, $level=1): string {
        $fields = [];
        foreach ($reflectionObject->getProperties() as $property) {
            if ($property['collection']) {
                continue;
            }
            if ($property['builtin']) {
                $alias = $reflectionObject->getObject()->getEntity() . $level;
                $field = $alias . '.' . $property['name'] . ' as ' . $alias .'__' . $property['name'];
            } else {
                $field = $this->_getFields(new ReflectionObject($property['type']), $level+1);
            }
            array_push($fields, $field);
        }
        return \join(", ", $fields);
    }

    public function getFrom(): string {
        $entity = $this->getEntity();
        $level = 1;
        $alias = "$entity$level";
        $joins = $this->getJoins($this->reflectionObject->getRelationFields(), $alias, $level+1);
        return " FROM `$entity` as $alias $joins ";
    }

    private function getJoins($relationFields, $parentAlias, $level): string {
        $result = '';
        foreach ($relationFields as $item) {
            $alias = "$item[2]$level";
            $result .= " LEFT JOIN `$item[2]` as $alias ON $parentAlias.$item[1] = $alias.id";
            $result .= $this->getJoins($item[3], $alias, $level+1);
        }
        return $result;
    }

    public function getInsert($object): array {
        $fieldsValues = new FieldsValues($this->reflectionObject, $object);
        $fieldsValues->prepare(true);
        return [
            'sql' => 'INSERT INTO ' .$this->getEntity(). ' (' .$fieldsValues->getStrFields(). ') VALUES (' .$fieldsValues->getStrValues(). ')',
            'queryParams' => $fieldsValues->getParams()
        ];
    }

    public function getUpdate(int $id, $object): array {
        $fieldsValues = new FieldsValues($this->reflectionObject, $object);
        $fieldsValues->prepare();
        return [
            'sql' => 'UPDATE ' .$this->getEntity(). ' SET ' .$fieldsValues->getStrUpdateValues(). ' WHERE id=:id',
            'queryParams' => array_merge($fieldsValues->getParams(), ['id' => $id])
        ];
    }

    public function getDelete(int $id): array {
        return [
            'sql' => 'DELETE FROM ' .$this->getEntity(). ' WHERE id=:id',
            'queryParams' => ['id' => $id]
        ];
    }

    public function getEntity(): string {
       return $this->reflectionObject->getObject()->getEntity();
    }

}