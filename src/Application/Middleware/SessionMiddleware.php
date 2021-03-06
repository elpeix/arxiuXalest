<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionMiddleware implements Middleware {

    public function process(Request $request, RequestHandler $handler): Response {
        session_save_path(SESSION_PATH);
        session_start();
        $request = $request->withAttribute('session', $_SESSION);

        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            // TODO JWT
        }

        return $handler->handle($request);
    }
}
