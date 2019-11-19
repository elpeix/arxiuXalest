<?php

namespace App\Models;

class Language extends BasicModel {

    public function entity(): string {
        return DB_PREFIX.'languages';
    }

}