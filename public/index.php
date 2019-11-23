<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use DI\ContainerBuilder;

use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\Responses\ResponseEmitter;

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

if (PHP_SAPI == 'cli-server') {
    $_SERVER['SCRIPT_NAME'] = basename(__FILE__);
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/Application/system.php';

if (DEBUG) {
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors', 'On');
    error_reporting(E_ALL);
}

require LANGUAGES_PATH . '/' . DEFAULT_LANGUAGE . '.php';

$containerBuilder = new ContainerBuilder();

$settings = require SRC_PATH . '/Application/settings.php';
$settings($containerBuilder);

$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

// Register Middleware
$middleware = require SRC_PATH . '/Application/Middleware/middleware.php';
$middleware($app);

// Register routes
$routes = require SRC_PATH . '/routes.php';
$routes($app);

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Error Handlers
$errorHandler = new HttpErrorHandler($callableResolver, $app->getResponseFactory());
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Middleware
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
