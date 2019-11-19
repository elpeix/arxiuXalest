<?php

namespace App\Application\Exception;

class BadRequestApiException extends ApiException {

    protected $code = 400;
    protected $message = 'Bad request.';
    protected $title = '400 Bad request';
    protected $description = 'Invalid request. Verify the params are correct.';

    public function __construct(string $message = null) {
        parent::__construct($message??$this->message, $this->code);
    }

}
