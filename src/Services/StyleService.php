<?php
namespace App\Services;

use App\Models\Style;

class StyleService extends BasicService {

    public function __construct() {
        parent::__construct('App\Models\Style');
    }

}