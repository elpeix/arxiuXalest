<?php

namespace App\Models;

use App\Application\Orm\Model;

abstract class BasicModel implements Model {

    public $id;
    public $name;

    public function __construct(?int $id = null, ?string $name = null) {
        if ($id != null) {
            $this->id = $id;
        }
        if ($name != null) {
            $this->name = $name;
        }
    }

    public function getAllowedOrderFields(): array {
        return ['id', 'name'];
    }

    public function jsonSerialize() {
        return [
            'id' => (int) $this->id,
            'name' => $this->name
        ];
    }
}