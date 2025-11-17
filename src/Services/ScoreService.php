<?php
namespace App\Services;

use App\Models\Score;
use App\Application\Exception\NotFoundApiException;

class ScoreService extends BasicService {

    private $entity;

    public function __construct() {
        parent::__construct('App\Models\Score');
    }

    public function getList(array $params, $args) {
        $this->initSelect($this->getBaseSelect());
        $this->sqlAdd(' WHERE 1 = 1');

        $this->setFilter($params);

        $this->setOrderBy($params);
        $this->setPagination($params);

        $count = $this->getCount();

        return [
            'count' => $count,
            APP_ITEMS_KEY => $count == 0 ? [] : $this->getQueryResults()
        ];
    }

    private function setFilter(array $params) {
        if (!isset($params['filter'])){
            return;
        }

        $paramFilter = $params['filter'];

        $conditionCount = 0;
        $qAnd = [];
        $queryParams = [];
        $andParts = explode(APP_FILTER_AND, $paramFilter);
        foreach ($andParts as $andPart) {
            $qOr = [];
            $orParts = explode(APP_FILTER_OR, $andPart);
            foreach ($orParts as $orPart) {
                $filterParam = explode("=", $orPart);
                if (count($filterParam) == 2) {
                    $conditionNames = $this->getConditionNames($filterParam[0], $conditionCount++);
                    if (count($conditionNames)) {
                        if (substr($conditionNames[0], -11) == APP_FILTER_LIKE) {
                            $conditionName = explode(APP_FILTER_LIKE, $conditionNames[0])[0];
                            array_push($qOr, " $conditionName LIKE $conditionNames[1]");
                            array_push($queryParams, [$conditionNames[1], "%" . $filterParam[1] . "%"]);
                        } else {
                            array_push($qOr, "$conditionNames[0] = $conditionNames[1]");
                            array_push($queryParams, [$conditionNames[1], $filterParam[1]]);
                        }
                    }
                }
            }
            if (count($qOr)) {
                array_push($qAnd, "(" . join(" OR ", $qOr) . ")");
            }
        }
        if (count($qAnd)) {
            $this->sqlAdd(' AND ' . join(" AND ", $qAnd));
            foreach ($queryParams as $querParam) {
                $this->addQueryParam($querParam[0], $querParam[1]);
            }
        }

        return;
    }

    private function getConditionNames($key, $conditionCount) {
        if (!$this->isValid($key)) {
            return [];
        }

        return [$this->getQueryField($key), ":condValue$conditionCount"];
    }
    
    private function isValid(string $property): bool {
        return $this->reflectionObject->getObject()->validateFilterField($property);
    }


}