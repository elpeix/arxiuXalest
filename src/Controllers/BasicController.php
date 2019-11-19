<?php
namespace App\Controllers;

use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Responses\ResponsePayload;
use App\Application\Exception\BadRequestApiException;
use App\Application\Exception\UnauthorizedApiException;
use App\Application\Controller;
use App\Services\ArmarisService;

abstract class BasicController extends Controller {

    public function getList(Request $request, Response $response, array $args): Response {
        $this->validatePermissions();
        $result = $this->getService()->getList($request->getQueryParams());
        return $this->ok($result, $response);
    }

    public function get(Request $request, Response $response, array $args): Response {
        $this->validatePermissions();
        $result = $this->getService()->getItem($args['id']);
        return $this->ok($result, $response);
    }

    public function create(Request $request, Response $response, array $args): Response {
        $this->validatePermissions(1);
        $result = $this->getService()->create($this->getBody($request));
        return $this->ok($result, $response);
    }

    public function update(Request $request, Response $response, array $args): Response {
        $this->validatePermissions(1);
        $result = $this->getService()->edit($args['id'], $this->getBody($request));
        return $this->ok($result, $response);
    }

    public function remove(Request $request, Response $response, array $args): Response {
        $this->validatePermissions(1);
        $this->getService()->remove($args['id']);
        return $this->okSuccess($response);
    }

    protected abstract function getService();

    protected function getBody(Request $request) {
        $body = $request->getParsedBody();
        foreach ($this->getRequiredFields() as $requiredField ) {
            if (!isset($body[$requiredField])){
                throw new BadRequestApiException("Validate error: $requiredField is required");
            }
        }
        return $body;
    }

    protected function getRequiredFields(): array {
        return ['name'];
    }
}