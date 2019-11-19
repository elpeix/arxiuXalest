<?php

namespace App\Application\Exception;

use \Exception;

class ApiException extends Exception {

    protected $title = '';
    protected $description = '';

    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }
}
