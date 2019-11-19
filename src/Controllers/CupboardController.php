<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Exception\BadRequestApiException;
use App\Services\CupboardService;

class CupboardController extends BasicController {

    private $service;

    public function __construct(){
        $this->service = new CupboardService();
    }

    protected function getService() {
        return $this->service;
    }

}