<?php

namespace App\Application\Responses;
use Psr\Http\Message\ResponseInterface as Response;

class ResponseJson {

    public function respond(ReponsePayload $payload, Response $response): Response {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
        var_dump($json);exit();
        $this->response->getBody()->write($json);
        return $this->response->withHeader('Content-Type', 'application/json');
    }
}