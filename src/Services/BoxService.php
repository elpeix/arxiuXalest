<?php
namespace App\Services;

use App\Models\Box;

class BoxService extends BasicService {

    public function __construct() {
        parent::__construct('App\Models\Box');
    }

}