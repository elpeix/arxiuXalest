<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Exception\BadRequestApiException;
use App\Services\ComposerService;

class ComposerController extends BasicController {

    private $service;

    public function __construct(){
        $this->service = new ComposerService();
    }

    protected function getService() {
        return $this->service;
    }

}