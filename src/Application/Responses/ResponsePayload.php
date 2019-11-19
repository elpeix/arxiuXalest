<?php
declare(strict_types=1);

namespace App\Application\Responses;

use JsonSerializable;

class ResponsePayload implements JsonSerializable {
    private $statusCode;
    private $data;
    private $error;

    public function __construct(int $statusCode = 200, $data = null, ?ResponseError $error = null) {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->error = $error;
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function getData() {
        return $this->data;
    }

    public function getError(): ?ResponseError {
        return $this->error;
    }

    public function jsonSerialize() {
        if ($this->data !== null) {
            return $this->data;
        }
        $payload = [
            'statusCode' => $this->statusCode,
        ];
        if ($this->error !== null) {
            $payload['error'] = $this->error;
        }
        return $payload;
    }
}
