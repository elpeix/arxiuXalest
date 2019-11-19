<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Exception\BadRequestApiException;
use App\Services\ChoirTypeService;

class ChoirTypeController extends BasicController {

    private $service;

    public function __construct(){
        $this->service = new ChoirTypeService();
    }

    protected function getService() {
        return $this->service;
    }

}