<?php

namespace App\Models;

class ChoirType extends BasicModel {

    public function entity(): string {
        return DB_PREFIX.'choirTypes';
    }

}