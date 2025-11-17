<?php

namespace App\Payloads;

use App\Application\Responses\Payload;

class ScorePayload implements Payload {

    public $id;
    public $name;
    public $century;
    public $box;
    public $cupboard;
    public $composer;
    public $style;
    public $language;
    public $lyricist;
    public $choirType;

    public function getId() {
        return $this->id;
    }

    public function getValue(string $key) {
        return $this->$key;
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'century' => $this->century,
            'box' => $this->box,
            'cupboard' => $this->cupboard,
            'style' => $this->style,
            'language' => $this->language,
            'lyricist' => $this->lyricist,
            'composer' => $this->composer,
            'choirType' => $this->choirType,
        ];
    }
}
