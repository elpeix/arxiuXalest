<?php

namespace App\Application\Orm;

use JsonSerializable;

interface Model extends JsonSerializable {

    public function entity(): string;

    public function getAllowedOrderFields(): array;

}