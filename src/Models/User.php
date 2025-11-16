<?php

namespace App\Models;

use App\Application\Orm\Model;

class User extends Model {

    public $entity = 'users';

    public int $id;
    public string $email;
    public string $firstName;
    public string $lastName;
    public string $username;
    public string $password;
    public ?\DateTime $lastLoginDate;
    public ?string $lastLoginAddress;
    public ?\DateTime $creationDate;
    public ?\DateTime $lastChangeDate;
    public ?int $visits;
    public ?int $level;
    public ?bool $enabled;

    public $attributes = [
        'id' => ['type' => 'int', 'primary' => true],
        'email' => ['type' => 'string'],
        'firstName' => ['type' => 'string'],
        'lastName' => ['type' => 'string'],
        'username' => ['type' => 'string'],
        'password' => ['type' => 'string'],
        'lastLoginDate' => ['type' => 'string'],
        'lastLoginAddress' => ['type' => 'string'],
        'creationDate' => ['type' => 'string'],
        'lastChangeDate' => ['type' => 'string'],
        'visits' => ['type' => 'int'],
        'level' => ['type' => 'int'],
        'enabled' => ['type' => 'bool']
    ];

    public function getAttributes(): array {
        return $this->attributes;
    }

    public function getAllowedOrderFields(): array {
        return ['id', 'email', 'fristName', 'lastName', 'enabled'];
    }

    public function getFields(): string {
        return 'id,email,firstName,lastName,username,lastLoginDate,lastLoginAddress,creationDate,lastChangeDate,visits,level,enabled';
    }

    public function getId(): int {
        return intval($this->getValue('id'));
    }

    public function isStaff(): bool {
        return $this->getValue('level') > 0 && $this->getValue('enabled');
    }

    public function jsonSerialize(): mixed {
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