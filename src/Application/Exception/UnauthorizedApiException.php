<?php

namespace App\Application\Exception;

class UnauthorizedApiException extends ApiException {

    protected $code = 401;
    protected $message = 'Unauthorized.';
    protected $title = '401 Unauthorized';
    protected $description = 'The request requires user authentication.';

    public function __construct() {
        parent::__construct($this->message, $this->code);
    }

}
