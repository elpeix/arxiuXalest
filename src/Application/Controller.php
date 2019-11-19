<?php

namespace App\Application;

use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Responses\ResponsePayload;
use App\Application\Exception\UnauthorizedApiException as Unauthorized;

abstract class Controller {

    private $STATUS_OK = 200;

    protected function ok($data, Response $response): Response {
        $payload = new ResponsePayload($this->STATUS_OK, $data);
        return $this->respond($payload, $response);
    }

    protected function okSuccess(Response $response): Response {
        $payload = new ResponsePayload($this->STATUS_OK, ["success" => true]);
        return $this->respond($payload, $response);
    }

    private function respond(ResponsePayload $payload, Response $response): Response {
        $json = json_encode($payload, JSON_INVALID_UTF8_IGNORE);
        $response->getBody()->write($json);
        return $response->withHeader('Content-Type', 'application/json');
    }

    protected function validatePermissions(int $level = 2) {
        if (!isset($_SESSION['user']) || $_SESSION['user']->level > $level) {
            throw new Unauthorized();
        }
    }
}