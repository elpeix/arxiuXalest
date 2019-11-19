<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Exception\BadRequestApiException;
use App\Services\UsersService;

class UsersController extends BasicController {

    private $service;
    private $requiredFields;

    public function __construct(){
        $this->service = new UsersService();
        $this->requiredFields = ['username','email'];
    }

    protected function getService() {
        return $this->service;
    }

    public function create(Request $request, Response $response, array $args): Response {
        \array_push($this->requiredFields, 'password');
        return parent::create($request, $response, $args);
    }

    protected function getBody(Request $request) {
        $body = $request->getParsedBody();
        foreach ($this->requiredFields as $requiredField ) {
            if (!isset($body[$requiredField])){
                throw new BadRequestApiException("Validate error: $requiredField is required");
            }
        }
        if (isset($body['email']) && !filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
            throw new BadRequestApiException("Validate error: email is not valid");
        }
        if (isset($body['password']) && !$this->validatePassword($body['password'])){
            throw new BadRequestApiException("Validate error: password is not valid. ".PASSWORD_VALIDATOR_MESSAGE);
        }
        return $body;
    }

    private function validatePassword(string $password): bool {
        return PASSWORD_VALIDATOR != '' && \preg_match(PASSWORD_VALIDATOR, $password);
    }

    protected function getRequiredFields(): array {
        return $this->requiredFields;
    }

    public function changePassword(Request $request, Response $response, array $args) {
        // TODO permissions
        $this->requiredFields = ['oldPassword', 'password'];
        $body = $this->getBody($request);

        if ($body['oldPassword'] == $body['password']){
            throw new BadRequestApiException("I think you don't want to change your password.");
        }
        \var_dump('edit password - TODO'); exit();
    }

}