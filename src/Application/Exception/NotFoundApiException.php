<?php

namespace App\Application\Exception;

class NotFoundApiException extends ApiException {

    protected $code = 404;
    protected $message = 'Not found.';
    protected $title = '404 Not Found';
    protected $description = 'The requested resource could not be found. Please verify the URI and try again.';

    public function __construct() {
        parent::__construct($this->message, $this->code);
    }

}
