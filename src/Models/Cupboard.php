<?php

namespace App\Models;

class Cupboard extends BasicModel {

    public function entity(): string {
        return DB_PREFIX.'cupboards';
    }

}