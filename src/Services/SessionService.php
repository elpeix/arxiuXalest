<?php
namespace App\Services;

use \PDO;
use App\Application\Orm\Database;
use App\Models\User;
use App\Application\Orm\Service;

class SessionService extends Service {

    private $dbConnection;

    public function __construct() {
        parent::__construct('App\Models\User');
    }


    public function getUser(string $username, string $password): ?User {
        $this->initSelect("SELECT ");
        $this->sqlAdd(' WHERE enabled=1 AND username=:username AND password=:password');
        $this->addQueryParam(':username', $username, \PDO::PARAM_STR);
        $this->addQueryParam(':password', \MD5($password), \PDO::PARAM_STR);

        $resultList = $this->getQueryResults();
        if (count($resultList) == 0) {
            return null;
        }
        return $resultList[0];
    }

}