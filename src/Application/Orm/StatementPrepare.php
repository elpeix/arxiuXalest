<?php

namespace App\Application\Orm;

use \ReflectionClass;

class StatementPrepare {

    private $reflectionObject;

    public function __construct(ReflectionObject $reflectionObject) {
        $this->reflectionObject = $reflectionObject;
    }

    public function getFrom(): string {
        return ' FROM ' .$this->getEntity(). ' ';
    }

    public function getInsert($object): array {
        $reflection = $this->reflectionObject->getReflection($object);
        $fieldsValues = new FieldsValues($reflection, $object);
        $fieldsValues->prepare();
        return [
            'sql' => 'INSERT INTO ' .$this->getEntity(). ' (' .$fieldsValues->getStrFields(). ') VALUES (' .$fieldsValues->getStrValues(). ')',
            'queryParams' => $fieldsValues->getParams()
        ];
    }

    public function getUpdate(int $id, $object): array {
        $reflection = $this->reflectionObject->getReflection($object);
        $fieldsValues = new FieldsValues($reflection, $object);
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

    private function getEntity(): string {
       return $this->reflectionObject->getObject()->entity();
    }

}