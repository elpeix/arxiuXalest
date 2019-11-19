<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\Application\Exception\BadRequestApiException;
use App\Services\LanguageService;

class LanguageController extends BasicController {

    private $service;

    public function __construct(){
        $this->service = new LanguageService();
    }

    protected function getService() {
        return $this->service;
    }

}