<?php

namespace App\Application\Orm;
use \PDO;

class Database {

    private $dbHost = DB_HOST;
    private $dbUser = DB_USER;
    private $dbPass = DB_PASS;
    private $dbName = DB_NAME;

    public function connect() {
        $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName;charset=utf8";
        $dbConnection = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }

}