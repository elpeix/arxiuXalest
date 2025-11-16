<?php

namespace App\Models;

use App\Application\Orm\Model;

abstract class BasicModel extends Model {

    public int $id;
    public ?string $name;

    public function __construct(?int $id = null, ?string $name = null) {
        if ($id != null) {
            $this->id = $id;
        }
        if ($name != null) {
            $this->name = $name;
        }
    }

    public function getAttributes(): array {
        return $this->attributes;
    }

    public function getAllowedOrderFields(): array {
        return ['id', 'name'];
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => (int) $this->id,
            'name' => $this->name
        ];
    }
}