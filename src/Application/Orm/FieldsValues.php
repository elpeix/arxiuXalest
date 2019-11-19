<?php

namespace App\Application\Orm;

use App\Application\Exception\BadRequestApiException;
use \ReflectionClass;
use \ReflectionProperty;

class FieldsValues {

    private $fields;
    private $values;
    private $params;
    private $updateValues;
    private $reflection;
    private $object;

    public function __construct(ReflectionClass $reflection, $object) {
        $this->reflection = $reflection;
        $this->object = $object;
        $this->fields = array();
        $this->values = array();
        $this->params = array();
        $this->updateValues = array();
    }
    
    public function prepare(bool $ignoreId = false) {
        foreach ($this->reflection->getProperties() as $property) {
            $value = $this->getValue($property);
            if ($value != null && ($ignoreId || $property->name != 'id')) {
                array_push($this->fields, $property->name);
                array_push($this->values, ':'.$property->name);
                array_push($this->updateValues, $property->name.'=:'.$property->name);
                $this->params[$property->name] = $value;
            }
        }
    }

    private function getValue(ReflectionProperty $property) {
        if ($property->isPublic()) {
            return $property->getValue($this->object);
        }
        $property->setAccessible(true);
        $value = $property->getValue($this->object);
        $property->setAccessible(false);
        return $value;
    }

    public function getStrFields(): string {
        return implode(',', $this->fields);
    }
    public function getStrValues(): string {
        return implode(',', $this->values);
    }
    public function getStrUpdateValues(): string {
        return implode(',', $this->updateValues);
    }
    public function getParams(): array {
        return $this->params;
    }

}