<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

use App\Controllers\CupboardController;
use App\Controllers\BoxController;
use App\Controllers\ComposerController;
use App\Controllers\StyleController;
use App\Controllers\LanguageController;
use App\Controllers\LyricistController;
use App\Controllers\ScoreController;
use App\Controllers\ChoirTypeController;
use App\Controllers\UsersController;
use App\Controllers\SessionController;

return function(App $app) {
    $app->get('/', function (Request $request, Response $response, $args) {
        $renderer = new Slim\Views\PhpRenderer(TEMPLATES_PATH, array(
            'appName' => APP_NAME,
            'language' => $GLOBALS['language']
        ));
        if (isset($_SESSION['user'])) {
            return $renderer->render($response, '/app.php', array('user'=> $_SESSION['user']));
        } else {
            return $renderer->render($response, '/login.php');
        }
    });
    
    $app->group('/cupboards', function(Group $group) {
        $controller = new CupboardController();
        $group->get('', CupboardController::class.':getList');
        $group->post('', CupboardController::class.':create');
        $group->get('/{id:[0-9]+}', CupboardController::class.':get');
        $group->put('/{id:[0-9]+}', CupboardController::class.':update');
        $group->delete('/{id:[0-9]+}', CupboardController::class.':remove');
    });

    $app->group('/boxes', function(Group $group) {
        $controller = new BoxController();
        $group->get('', BoxController::class.':getList');
        $group->post('', BoxController::class.':create');
        $group->get('/{id:[0-9]+}', BoxController::class.':get');
        $group->put('/{id:[0-9]+}', BoxController::class.':update');
        $group->delete('/{id:[0-9]+}', BoxController::class.':remove');
    });

    $app->group('/composers', function(Group $group) {
        $controller = new ComposerController();
        $group->get('', ComposerController::class.':getList');
        $group->post('', ComposerController::class.':create');
        $group->get('/{id:[0-9]+}', ComposerController::class.':get');
        $group->put('/{id:[0-9]+}', ComposerController::class.':update');
        $group->delete('/{id:[0-9]+}', ComposerController::class.':remove');
    });

    $app->group('/styles', function(Group $group) {
        $controller = new StyleController();
        $group->get('', StyleController::class.':getList');
        $group->post('', StyleController::class.':create');
        $group->get('/{id:[0-9]+}', StyleController::class.':get');
        $group->put('/{id:[0-9]+}', StyleController::class.':update');
        $group->delete('/{id:[0-9]+}', StyleController::class.':remove');
    });

    $app->group('/languages', function(Group $group) {
        $controller = new LanguageController();   
        $group->get('', LanguageController::class.':getList');
        $group->post('', LanguageController::class.':create');
        $group->get('/{id:[0-9]+}', LanguageController::class.':get');
        $group->put('/{id:[0-9]+}', LanguageController::class.':update');
        $group->delete('/{id:[0-9]+}', LanguageController::class.':remove');
    });

    $app->group('/lyricists', function(Group $group) {
        $controller = new LyricistController();
        $group->get('', LyricistController::class.':getList');
        $group->post('', LyricistController::class.':create');
        $group->get('/{id:[0-9]+}', LyricistController::class.':get');
        $group->put('/{id:[0-9]+}', LyricistController::class.':update');
        $group->delete('/{id:[0-9]+}', LyricistController::class.':remove');
    });

    $app->group('/scores', function(Group $group) {
        $group->get('', ScoreController::class.':getList');
        $group->post('', ScoreController::class.':create');
        $group->get('/{id:[0-9]+}', ScoreController::class.':get');
        $group->put('/{id:[0-9]+}', ScoreController::class.':update');
        $group->delete('/{id:[0-9]+}', ScoreController::class.':remove');
    });

    $app->group('/choirTypes', function(Group $group) {
        $group->get('', ChoirTypeController::class.':getList');
        $group->post('', ChoirTypeController::class.':create');
        $group->get('/{id:[0-9]+}', ChoirTypeController::class.':get');
        $group->put('/{id:[0-9]+}', ChoirTypeController::class.':update');
        $group->delete('/{id:[0-9]+}', ChoirTypeController::class.':remove');
    });

    $app->group('/users', function(Group $group) {
        $group->get('', UsersController::class.':getList');
        $group->post('', UsersController::class.':create');
        $group->get('/{id:[0-9]+}', UsersController::class.':get');
        $group->put('/{id:[0-9]+}', UsersController::class.':update');
        $group->delete('/{id:[0-9]+}', UsersController::class.':remove');
        $group->post('/changePassword', UsersController::class.':changePassword');
    });

    $app->post('/login', SessionController::class.':login');
    $app->post('/logout', SessionController::class.':logout');

};
