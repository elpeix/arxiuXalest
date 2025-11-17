<?php

namespace App\Payloads;

use App\Application\Responses\Payload;

class UserPayload implements Payload {

    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $isStaff;
    public $level;

    public function getId() {
        return $this->id;
    }

    public function getValue(string $key) {
        return $this->$key;
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'isStaff' => $this->isStaff,
            'level' => $this->level
        ];
    }
}
