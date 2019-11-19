<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Exception\BadRequestApiException;
use App\Services\StyleService;

class StyleController extends BasicController {

    private $service;

    public function __construct(){
        $this->service = new StyleService();
    }

    protected function getService() {
        return $this->service;
    }

}