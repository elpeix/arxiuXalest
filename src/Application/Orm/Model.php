<?php

namespace App\Application\Orm;

use JsonSerializable;

abstract class Model implements JsonSerializable {

    public function __construct(?int $id=0) {
        if (isset($id) && $id > 0) {
            $this->setId($id);
        }
    }

    public function getValue(string $name, $rawValue = false) {
        $attributes = $this->getAttributes();
        if (!isset($attributes[$name])) {
            return '';
            throw new \Exception("Attribute $name is not present", 1);
        }
        if (!isset($attributes[$name]['value'])) {
            return '';
            throw new \Exception("Attribute [$name] is not initialized", 1);
        }
        $value = $attributes[$name]['value'];
        if ($attributes[$name]['type'] == 'bool') {
            return $rawValue ? $value : boolval($value);
        }
        return $value;
    }

    public function getId() {
        return intval($this->getValue('id'));
    }

    public function setId(int $id) {
        return intval($this->setValue('id', $id));
    }

    public function setValue(string $name, $value) {
        if (!isset($this->attributes[$name])) {
            return '';
            throw new \Exception("Attribute $name is not present", 1);
        }
        if ($this->attributes[$name]['type'] === 'bool') {
            $value = $value ? 1 : 0;
        }
        $this->attributes[$name]['value'] = $value;
    }

    public function getEntity(): string {
        if (DB_PREFIX !== null && strlen(DB_PREFIX)) {
            return DB_PREFIX . "_" . $this->entity;
        }
        return $this->entity;
    }

    public abstract function getAttributes(): array;

}