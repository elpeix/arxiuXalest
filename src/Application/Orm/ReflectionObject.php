<?php

namespace App\Application\Orm;

use App\Application\Exception\BadRequestApiException;
use \ReflectionClass;
use \ReflectionProperty;

class ReflectionObject {

    private $model;
    private $objModel;

    public function __construct(string $model) {
        $reflect = new ReflectionClass($model);
        $this->model = $model;
        $this->objModel = $reflect->newInstance();
    }

    public function getReflection($object) {
        $reflection = new ReflectionClass($object);
        if ($reflection->getName() != $this->model) {
            throw new BadRequestApiException();
        }

        return $reflection;
    }

    public function getModel() {
        return $this->model;
    }

    public function getObject() {
        return $this->objModel;
    }

    public function createObject() {
        $reflect = new ReflectionClass($this->model);
        return $reflect->newInstance();
    }

}