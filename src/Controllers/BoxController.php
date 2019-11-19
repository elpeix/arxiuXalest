<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Exception\BadRequestApiException;
use App\Services\BoxService;

class BoxController extends BasicController {

    private $service;

    public function __construct(){
        $this->service = new BoxService();
    }

    protected function getService() {
        return $this->service;
    }

}