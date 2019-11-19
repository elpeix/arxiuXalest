<?php

namespace App\Services;

use App\Models\Caixes;
use App\Application\Exception\NotFoundApiException;
use App\Application\Exception\BadRequestApiException;
use App\Application\Orm\Service;
use \PDO;

abstract class BasicService extends Service {

    public function getList(array $params) {
        $this->initSelect($this->getBaseSelect());
        $this->setOrderBy($params);
        $this->setPagination($params);
        return [
            'count' => $this->getCount(),
            'items' => $this->getQueryResults()
        ];
    }

    public function getItem(int $id) {
        $this->initSelect($this->getBaseSelect());
        $this->sqlAdd(' WHERE id = :id');
        $this->addQueryParam(':id', $id);
        $resultList = $this->getQueryResults();
        if (count($resultList) == 0) {
            throw new NotFoundApiException();
        }
        return $resultList[0];
    }

    protected function getBaseSelect():string {
        return "SELECT id, name ";
    }

    public function create(array $body) {
        $obj = $this->createObject();
        $obj->name = $body['name'];
        $id = $this->insert($obj);
        $obj->id = $id;
        return $obj;
    }
    
    public function edit(int $id, array $body) {
        $obj = $this->getItem($id);
        $obj->name = $body['name'];
        $this->update($id, $obj);
        return $obj;
    }

    public function remove(int $id) {
        $armari = $this->getItem($id);
        $this->delete($id);
    }
}