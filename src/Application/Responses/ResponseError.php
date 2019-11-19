<?php
declare(strict_types=1);

namespace App\Application\Responses;

use JsonSerializable;

class ResponseError implements JsonSerializable {
    public const BAD_REQUEST = 'BAD_REQUEST';
    public const INSUFFICIENT_PRIVILEGES = 'INSUFFICIENT_PRIVILEGES';
    public const NOT_ALLOWED = 'NOT_ALLOWED';
    public const NOT_IMPLEMENTED = 'NOT_IMPLEMENTED';
    public const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    public const SERVER_ERROR = 'SERVER_ERROR';
    public const UNAUTHENTICATED = 'UNAUTHENTICATED';
    public const VALIDATION_ERROR = 'VALIDATION_ERROR';
    public const VERIFICATION_ERROR = 'VERIFICATION_ERROR';

    private $type;
    private $description;
    private $errorData;

    public function __construct(string $type, ?string $description) {
        $this->type = $type;
        $this->description = $description;
    }

    public function getType(): string {
        return $this->type;
    }

    public function setType(string $type): self {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(?string $description = null): self {
        $this->description = $description;
        return $this;
    }
    public function setErrorData(array $errorData): self {
        $this->errorData = $errorData;
        return $this;
    }

    public function jsonSerialize() {
        $payload = [
            'type' => $this->type,
            'description' => $this->description,
        ];
        if (DEVEL && $this->errorData !== null) {
            $payload['data'] = $this->errorData;
        }
        return $payload;
    }
}
