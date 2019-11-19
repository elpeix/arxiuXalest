<?php
namespace App\Services;

use App\Models\Cupboard;

class CupboardService extends BasicService {

    public function __construct() {
        parent::__construct('App\Models\Cupboard');
    }

}