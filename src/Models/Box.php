<?php

namespace App\Models;

class Box extends BasicModel {

    public function entity(): string {
        return DB_PREFIX.'boxes';
    }

}