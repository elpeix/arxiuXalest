<?php

namespace App\Models;

use App\Application\Orm\Model;

abstract class BasicModel extends Model {

    public int $id;
    public ?string $name;

    public $attributes = [
        'id' => ['type' => 'int', 'primary' => true],
        'name' => ['type' => 'string']
    ];

    public function getAttributes(): array {
        return $this->attributes;
    }

    public function getAllowedOrderFields(): array {
        return ['id', 'name'];
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => (int) $this->getValue('id'),
            'name' => $this->getValue('name')
        ];
    }
}