<?php
declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Responses\ResponseError;
use App\Application\Responses\ResponsePayload;
use App\Application\Exception\ApiException;
use App\Application\Exception\NotFoundApiException;
use App\Application\Exception\UnauthorizedApiException;
use App\Application\Exception\BadRequestApiException;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Throwable;

class HttpErrorHandler extends SlimErrorHandler {
    /**
     * @inheritdoc
     */
    protected function respond(): Response
    {
        $exception = $this->exception;
        $statusCode = 500;
        $error = new ResponseError(
            ResponseError::SERVER_ERROR,
            'An internal error has occurred while processing your request.'
        );

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $error->setDescription($exception->getMessage());

            if ($exception instanceof HttpNotFoundException) {
                $error->setType(ResponseError::RESOURCE_NOT_FOUND);
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $error->setType(ResponseError::NOT_ALLOWED);
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $error->setType(ResponseError::UNAUTHENTICATED);
            } elseif ($exception instanceof HttpForbiddenException) {
                $error->setType(ResponseError::INSUFFICIENT_PRIVILEGES);
            } elseif ($exception instanceof HttpBadRequestException) {
                $error->setType(ResponseError::BAD_REQUEST);
            } elseif ($exception instanceof HttpNotImplementedException) {
                $error->setType(ResponseError::NOT_IMPLEMENTED);
            }
        } elseif ($exception instanceof ApiException) {
            $statusCode = $exception->getCode();
            $error->setDescription($exception->getMessage());

            if ($exception instanceof NotFoundApiException) {
                $error->setType(ResponseError::RESOURCE_NOT_FOUND);
            } elseif ($exception instanceof UnauthorizedApiException) {
                $error->setType('UNAUTHORIZED');
            } elseif ($exception instanceof BadRequestApiException) {
                $error->setType(ResponseError::BAD_REQUEST);
            }
        }

        if (
            !($exception instanceof HttpException)
            && ($exception instanceof Exception || $exception instanceof Throwable)
            && $this->displayErrorDetails
        ) {
            $error->setDescription($exception->getMessage());
        }

        if (DEVEL) {
            $error->setErrorData([
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ]);
        }

        $payload = new ResponsePayload($statusCode, null, $error);
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);


        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
