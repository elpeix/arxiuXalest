<?php

namespace App\Models;

use App\Application\Orm\Model;

class User implements Model {

    public $id;
    public $email;
    public $firstName;
    public $lastName;
    public $username;
    public $password;
    public $lastLoginDate;
    public $lastLoginAddress;
    public $creationDate;
    public $lastChangeDate;
    public $visits;
    public $level;
    public $enabled;

    public function entity(): string {
        return DB_PREFIX.'users';
    }

    public function getAllowedOrderFields(): array {
        return ['id', 'email', 'fristName', 'lastName', 'enabled'];
    }

    public function getFields(): string {
        return 'id,email,firstName,lastName,username,lastLoginDate,lastLoginAddress,creationDate,lastChangeDate,visits,level,enabled';
    }

    public function jsonSerialize() {
        return [
            'id' => (int) $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'username' => $this->username,
            'lastLoginDate' => $this->lastLoginDate,
            'lastLoginAddress' => $this->lastLoginAddress,
            'visits' => (int) $this->visits
        ];
    }
}