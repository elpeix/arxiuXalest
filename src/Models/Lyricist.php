<?php

namespace App\Models;

class Lyricist extends BasicModel {

    public function entity(): string {
        return DB_PREFIX.'lyricists';
    }

}