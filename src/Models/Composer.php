<?php

namespace App\Models;

class Composer extends BasicModel {

    public function entity(): string {
        return DB_PREFIX.'composers';
    }

}