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

    public function __construct(ReflectionObject $reflection, $object) {
        $this->reflection = $reflection;
        $this->object = $object;
        $this->fields = array();
        $this->values = array();
        $this->params = array();
        $this->updateValues = array();
    }
    
    public function prepare(bool $ignorePrimary = true) {
        foreach ($this->reflection->getProperties() as $property) {
            if ($property['builtin']) {
                $fieldName = $property['name'];
                $value = $this->getValue($fieldName);
                if (isset($value) && (!$this->isPrimary($property) || (!$ignorePrimary && $this->isPrimary($property)))) {
                    array_push($this->fields, $fieldName);
                    array_push($this->values, ':'.$fieldName);
                    array_push($this->updateValues, $fieldName.'=:'.$fieldName);
                    $this->params[$fieldName] = $value;
                }
            } else {
                $fieldName = $property['relation'];
                $objValue = $this->getValue($property['name']);
                if ($objValue != null) {
                    $value = $objValue->getValue('id');
                    array_push($this->fields, $fieldName);
                    array_push($this->values, ':' . $fieldName);
                    array_push($this->updateValues, $fieldName . '=:' . $fieldName);
                    $this->params[$fieldName] = ($value == null) ? null : $value;
                }
            }
        }
    }

    private function isPrimary($property) {
        return isset($property['primary']) && $property['primary'];
    }

    private function getValue(string $fieldName) {
        try {
            return $this->object->getValue($fieldName, true);
        } catch (\Exception $e) {
            return null;
        }
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
