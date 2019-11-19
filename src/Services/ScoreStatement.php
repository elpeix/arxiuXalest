<?php
namespace App\Services;

use App\Models\Score;

class ScoreStatement {

    private $entity;
    private $data;
    private $fieldValues;
    private $queryParams;

    public function __construct(string $entity, array $body) {
        $this->entity = $entity;
        $this->data = array();
        $this->queryParams = array();
        $this->prepare($body);
    }

    private function prepare(array $body) {
        $this->data['name'] = $body['name'];
        $this->data['century'] = isset($body['century']) ? $body['century'] : null;

        foreach (Score::getRelationFields() as $item) {
            $this->data[$item[1] . "Id"] = isset($body[$item[1]]) ? (int) $body[$item[1]] : null;
        }
        foreach ($this->data as $key => $value) {
            if ($value != null) {
                $this->queryParams[":$key"] = $value;
            }
        }
    }

    public function getInsertStatement() {
        $fields = array();
        $fieldValues = array();
        foreach ($this->data as $key => $value) {
            if ($value != null) {
                \array_push($fields, $key);
                \array_push($fieldValues, ":$key");
            }
        }
        return 'INSERT INTO '.$this->entity.' (' .implode(', ', $fields). ') VALUES (' .implode(', ', $fieldValues).')';
    }

    public function getUpdateStatement() {
        $fields = array();
        foreach ($this->data as $key => $value) {
            if ($value != null) {
                \array_push($fields, $key.'=:'.$key);
            }
        }
        return 'UPDATE '.$this->entity.' SET ' .implode(', ', $fields). ' WHERE id=:id';
    }

    public function getQueryParams() {
        return $this->queryParams;
    }
}