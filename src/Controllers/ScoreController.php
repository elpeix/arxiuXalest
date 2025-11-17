<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Exception\BadRequestApiException;
use App\Services\ScoreService;
use App\Ensemblers\ScoreEnsembler;
use App\Models\Score;
use App\Models\Composer;
use App\Models\Lyricist;
use App\Models\Style;
use App\Models\Language;
use App\Models\ChoirType;
use App\Models\Cupboard;
use App\Models\Box;

class ScoreController extends BasicController {

    private $service;
    private $ensembler;

    public function __construct(){
        $this->service = new ScoreService();
        $this->ensembler = new ScoreEnsembler();
    }

    protected function getService() {
        return $this->service;
    }

    public function getList(Request $request, Response $response, array $args): Response {
        $this->validatePermissions();
        $scores = $this->getScores($request->getQueryParams(), $args);
        $scores[APP_ITEMS_KEY] = array_map(array($this, 'ensemble'), $scores[APP_ITEMS_KEY]);
        return $this->ok($scores, $response);
    }

    protected function getScores(array $params, $args) {
        return $this->service->getList($params, $args);
    }

    public function read(Request $request, Response $response, array $args): Response {
        $this->validatePermissions();
        return $this->getScore($args['id'], $response);
    }

    private function getScore($id, Response $response) {
        $score = $this->service->getItem($id);
        return $this->ok($this->ensemble($score), $response);
    }

    protected function ensemble(Score $score) {
        return $this->ensembler->ensemble($score);
    }

    public function create(Request $request, Response $response, array $args): Response {
        $this->validatePermissions(1);
        $body = $this->getBody($request);
        $score = $this->fillScore($body, new Score());
        $scoreId = $this->getService()->insert($score);
        return $this->getScore($scoreId, $response);
    }
    
    private function fillScore($body, Score $score) {
        $score->setValue('name', $body['name']);
        $score->setValue('century', $body['century']);
  
        if (isset($body['composer']) && intval($body['composer']) > 0) {
            $score->setValue('composer', new Composer($body['composer']));
        }
        if (isset($body['lyricist']) && intval($body['lyricist']) > 0) {
            $score->setValue('lyricist', new Lyricist($body['lyricist']));
        }
        if (isset($body['style']) && intval($body['style']) > 0) {
            $score->setValue('style', new Style($body['style']));
        }
        if (isset($body['choirType']) && intval($body['choirType']) > 0) {
            $score->setValue('choirType', new ChoirType($body['choirType']));
        }
        if (isset($body['language']) && intval($body['language']) > 0) {
            $score->setValue('language', new Language($body['language']));
        }
        if (isset($body['cupboard']) && intval($body['cupboard']) > 0) {
            $score->setValue('cupboard', new Cupboard($body['cupboard']));
        }
        if (isset($body['box']) && intval($body['box']) > 0) {
            $score->setValue('box', new Box($body['box']));
        }

        return $score; 
    }

    protected function getRequiredFields(): array {
        return ['name', 'composer'];
    }


    public function update(Request $request, Response $response, array $args): Response {
        $this->validatePermissions(1);
        $scoreId = $args['id'];
        $score = $this->service->getItem($scoreId);
        $body = $this->getBody($request);
        $score = $this->fillScore($body, $score);
        $this->getService()->update($scoreId, $score);

        return $this->getScore($scoreId, $response);
    }

    public function delete(Request $request, Response $response, array $args): Response {
        $this->validatePermissions(1);
        $this->getService()->remove($args['id']);

        return $this->okSuccess($response);
    }
}