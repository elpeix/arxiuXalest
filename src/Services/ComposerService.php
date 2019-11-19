<?php
namespace App\Services;

use App\Models\Composer;

class ComposerService extends BasicService {

    public function __construct() {
        parent::__construct('App\Models\Composer');
    }

}