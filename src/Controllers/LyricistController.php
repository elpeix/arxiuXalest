<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Exception\BadRequestApiException;
use App\Services\LyricistService;

class LyricistController extends BasicController {

    private $service;

    public function __construct(){
        $this->service = new LyricistService();
    }

    protected function getService() {
        return $this->service;
    }

}