<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Exception\BadRequestApiException;
use App\Services\ScoreService;

class ScoreController extends BasicController {

    private $service;

    public function __construct(){
        $this->service = new ScoreService();
    }

    protected function getService() {
        return $this->service;
    }

    protected function getRequiredFields(): array {
        return ['name', 'composer'];
    }
}