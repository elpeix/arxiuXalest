<?php

namespace App\Models;

class Style extends BasicModel {

    public function entity(): string {
        return DB_PREFIX.'styles';
    }

}