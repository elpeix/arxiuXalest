<?php
namespace App\Services;

use App\Models\Language;

class LanguageService extends BasicService {

    public function __construct() {
        parent::__construct('App\Models\Language');
    }

}