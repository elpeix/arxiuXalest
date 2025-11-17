<?php

namespace App\Services;

use App\Models\Caixes;
use App\Application\Exception\NotFoundApiException;
use App\Application\Exception\BadRequestApiException;
use App\Application\Orm\Service;
use App\Application\Orm\Model;
use \PDO;

abstract class BasicService extends Service {

    public function getList(array $params, $args) {
        $this->initSelect($this->getBaseSelect());
        if (isset($params['name'])) {
            $this->sqlAdd(' WHERE ' . $this->getAlias() . '.name LIKE :name');
            $this->addQueryParam(':name', "%".$params['name']."%");
        }
        $this->setOrderBy($params);
        $this->setPagination($params);

        $count = $this->getCount();
        if ($count == 0) {
            $result = [
                'count' => $count,
                APP_ITEMS_KEY => []
            ];
            if (isset($params['name'])) {
                $result['similarItems'] = $this->getSimilarItems($params['name']);
            }
            return $result;
        }
        return [
            'count' => $count,
            APP_ITEMS_KEY => $this->getQueryResults()
        ];
    }

    private function getSimilarItems($input) {
        $resultList = $this->queryExecuter->executeQuery(
            "SELECT `id`, `name` FROM " . $this->statementPreparer->getEntity()
        );
        $similarNames = [];
        foreach ($resultList as $row) {
            $name = $row['name'];
            $item = [
                'id' => $row['id'],
                'name' => $name
            ];
            similar_text($input, $name, $percent);
            if ($percent >= 50) {
                array_push($similarNames, $item);
                continue;
            }
            $expNames = explode(" ", $name);
            foreach ($expNames as $subName) {
                similar_text($input, $subName, $percent);
                if ($percent >= 66) {
                    array_push($similarNames, $item);
                    break;
                }
            }
        }
        return $similarNames;
    }

    public function getItem(int $id): Model {
        $this->initSelect($this->getBaseSelect());
        $this->sqlAdd(' WHERE '. $this->getAlias() .'.id = :id');
        $this->addQueryParam(':id', $id);
        $resultList = $this->getQueryResults();
        if (count($resultList) == 0) {
            throw new NotFoundApiException();
        }
        return $resultList[0];
    }

    protected function getBaseSelect():string {
        return "SELECT ";
    }

    public function create(array $body) {
        $obj = $this->createObject();
        $obj->setValue('name', $body['name']);
        $id = $this->insert($obj);
        $obj->setValue('id', $id);
        return $obj;
    }

    public function edit(int $id, array $body) {
        $obj = $this->getItem($id);
        $obj->setValue('name', $body['name']);
        $this->update($id, $obj);
        return $obj;
    }

    public function remove(int $id) {
        $this->getItem($id);
        $this->delete($id);
    }

}