<?php

namespace App\Services;

use App\Models\User;
use App\Application\Exception\NotFoundApiException;
use App\Application\Exception\BadRequestApiException;
use App\Application\Orm\Service;
use \PDO;

class UsersService extends Service {

    public function __construct() {
        parent::__construct('App\Models\User');
    }

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
        return 'SELECT ' . $this->reflectionObject->getObject()->getFields() . ' ';
    }

    public function create(array $body) {
        $obj = $this->createObject();
        $this->setProperties($obj, $body);
        $obj->creationDate = \date('Y-m-d H:i:s');
        $obj->enabled = 1;
        $id = $this->insert($obj);
        $obj->id = $id;
        return $obj;
    }
    
    public function edit(int $id, array $body) {
        $obj = $this->getItem($id);
        $this->setProperties($obj, $body);
        $obj->enabled = $body['enabled'] ?? null;
        $this->update($id, $obj);
        return $obj;
    }

    private function setProperties($obj, array $body) {
        $obj->email = $body['email'];
        $obj->username = $body['username'];
        $obj->firstName = $body['firstName'] ?? $obj->firstName;
        $obj->lastName = $body['lastName'] ?? $obj->lastName;
        if (isset($body['password'])) {
            $obj->password = MD5($body['password']);
        }
        $obj->lastChangeDate = \date(SQL_DATE_FORMAT);
        $obj->level = $body['level'] ?? $obj->level;
    }

    public function remove(int $id) {
        $armari = $this->getItem($id);
        $this->delete($id);
    }
}