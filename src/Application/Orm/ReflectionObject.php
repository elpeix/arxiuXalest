<?php

namespace App\Application\Orm;

use App\Application\Exception\BadRequestApiException;
use \ReflectionClass;

class ReflectionObject {

    private $reflect;
    private $model;
    private $objModel;

    public function __construct(string $model) {
        $this->reflect = new ReflectionClass($model);
        $this->model = $model;
        $this->objModel = $this->createObject();
    }

    public function getReflection($object) {
        $reflection = new ReflectionClass($object);
        if ($reflection->getName() != $this->model) {
            throw new BadRequestApiException();
        }
        return $reflection;
    }

    public function getName() {
        return $this->reflect->getName();
    }

    public function getModel() {
        return $this->model;
    }

    public function getEntity() {
        return $this->objModel->getEntity();
    }

    public function getObject() {
        return $this->objModel;
    }

    public function createObject() {
        return $this->reflect->newInstance();
    }

    public function getRelationFields() {
        $relations = [];
        foreach ($this->getAttributes() as $key => $value) {
            if (isset($value['relatedBy'])) {
                continue;
            }
            if (isset($value['relation']) && (!isset($value['collection']) || !$value['collection'])) {
                $reflectionObject = new ReflectionObject($value['type']);
                array_push($relations, [
                    $key,
                    $value['relation'],
                    $reflectionObject->getEntity(),
                    $reflectionObject->getRelationFields()
                ]);
            }
        }
        return $relations;
    }

    private function getAttributes() {
        $property = $this->reflect->getProperty('attributes');
        return $property->getValue($this->objModel);
    }

    public function getProperties() {
        $result = [];
        foreach ($this->getAttributes() as $key => $value) {
            $property = [
                'name' => $key,
                'type' => $value['type'],
                'primary' => isset($value['primary']) && $value['primary'],
                'relation' => isset($value['relation']) ? $value['relation'] : '',
                'collection' => isset($value['collection']) && $value['collection'],
                'builtin' => \preg_match('/^[^\\\]+$/', $value['type']) === 1,
                'nullable' => isset($value['nullable']) && $value['nullable']
            ];
            if (isset($value['relatedBy'])) {
                $property['relatedBy'] = $value['relatedBy'];
            }
            array_push($result, $property);
        }
        return $result;
    }

}