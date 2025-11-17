<?php

namespace App\Application\Exception;

class InternalException extends ApiException {

    protected $code = 500;
    protected $message = 'Server error.';
    protected $title = '500 Server error';
    protected $description = 'Server error.';

    public function __construct(?string $message) {
        parent::__construct($message ?? $this->message, $this->code);
    }
}
