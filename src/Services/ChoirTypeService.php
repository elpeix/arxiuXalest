<?php
namespace App\Services;

use App\Models\ChoirType;

class ChoirTypeService extends BasicService {

    public function __construct() {
        parent::__construct('App\Models\ChoirType');
    }

}