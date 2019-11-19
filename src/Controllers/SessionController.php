<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Controller;
use App\Application\Exception\BadRequestApiException;
use App\Services\SessionService;

class SessionController extends Controller {

    public function login(Request $request, Response $response, array $args): Response {
        $body = $this->getBody($request);
        $service = new SessionService();
        $user = $service->getUser($body['username'], $body['password']);
        if ($user == null) {
            throw new BadRequestApiException("Invalid login.");
        }
        $user->lastLoginDate = date(SQL_DATE_FORMAT);
        $user->lastLoginAddress = $_SERVER['REMOTE_ADDR'];
        $user->visits = $user->visits + 1;

        $service->updateLogin($user);
        $_SESSION['user'] = $user;
        return $this->ok($user, $response);
    }

    public function logout(Request $request, Response $response, array $args): Response {
        unset($_SESSION['user']);
        \session_destroy();
        return $this->okSuccess($response);
    }

    protected function getBody(Request $request) {
        $body = $request->getParsedBody();
        foreach (['username', 'password'] as $requiredField ) {
            if (!isset($body[$requiredField])){
                throw new BadRequestApiException("Validate error: $requiredField is required");
            }
        }
        return $body;
    }
}