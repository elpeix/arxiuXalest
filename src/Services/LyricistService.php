<?php
namespace App\Services;

use App\Models\Lyricist;

class LyricistService extends BasicService {

    public function __construct() {
        parent::__construct('App\Models\Lyricist');
    }

}