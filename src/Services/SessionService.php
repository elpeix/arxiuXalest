<?php
namespace App\Services;

use \PDO;
use App\Application\Orm\Database;
use App\Models\User;

class SessionService {

    private $dbConnection;

    public function __construct() {
        $db = new Database();
        $this->objModel = new User();
        $this->dbConnection = $db->connect();
    }

    public function getUser(string $username, string $password): ?User {
        $sql = 'SELECT '.$this->objModel->getFields().' FROM ' .$this->objModel->entity();
        $sql .=' WHERE enabled=1 AND username=:username AND password=:password';
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':password', MD5($password), PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'App\Models\User');
        $stmt->execute();
        if ($stmt->rowCount()) {
            return $stmt->fetch();
        }
        return null;
    }

    public function updateLogin(User $user) {
        try {
            $sql = 'UPDATE ' .$this->objModel->entity();
            $sql .=' SET lastLoginDate=:lastLoginDate, lastLoginAddress=:lastLoginAddress, visits=:visits';
            $sql .= ' WHERE id=:id';
            $this->dbConnection->beginTransaction();
            $stmt = $this->dbConnection->prepare($sql);
            $stmt->bindValue(':lastLoginDate', $user->lastLoginDate, PDO::PARAM_STR);
            $stmt->bindValue(':lastLoginAddress',$user->lastLoginAddress, PDO::PARAM_STR);
            $stmt->bindValue(':visits', (int) $user->visits, PDO::PARAM_INT);
            $stmt->bindValue(':id', (int) $user->id, PDO::PARAM_INT);
            $stmt->execute();
            $this->dbConnection->commit();
        } catch (Exception $e) {
            $this->dbConnection->rollback();
        }
    }

}