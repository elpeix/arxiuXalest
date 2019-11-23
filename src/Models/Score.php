<?php

namespace App\Models;

use App\Application\Orm\Model;

class Score implements Model {

    public $id;
    public $name;
    public $century;
    public $cupboard;
    public $box;
    public $composer;
    public $style;
    public $language;
    public $lyricist;
    public $choirType;

    public static function createObject(array $obj): Score {
        $score = new Score();
        $score->id = $obj['_id'];
        $score->name = $obj['_name'];
        $score->century = $obj['_century'];
        if ($obj['cupboard_id'] > 0) {
            $score->cupboard = new Cupboard($obj['cupboard_id'], $obj['cupboard_name']);
        }
        if ($obj['box_id'] > 0) {
            $score->box = new Box($obj['box_id'], $obj['box_name']);
        }
        if ($obj['composer_id'] > 0) {
            $score->composer = new Composer($obj['composer_id'], $obj['composer_name']);
        }
        if ($obj['style_id'] > 0) {
            $score->style = new Style($obj['style_id'], $obj['style_name']);
        }
        if ($obj['language_id'] > 0) {
            $score->language = new Language($obj['language_id'], $obj['language_name']);
        }
        if ($obj['lyricist_id'] > 0) {
            $score->lyricist = new Lyricist($obj['lyricist_id'], $obj['lyricist_name']);
        }
        if ($obj['choirType_id'] > 0) {
            $score->choirType = new ChoirType($obj['choirType_id'], $obj['choirType_name']);
        }

        return $score;
    }

    public static function getRelationFields(): array {
        return array(
            [DB_PREFIX.'cupboards', 'cupboard'],
            [DB_PREFIX.'boxes', 'box'],
            [DB_PREFIX.'composers', 'composer'],
            [DB_PREFIX.'styles', 'style'],
            [DB_PREFIX.'languages', 'language'],
            [DB_PREFIX.'lyricists', 'lyricist'],
            [DB_PREFIX.'choirTypes', 'choirType'],
        );
    }

    public function validateFilterField(string $field): bool {
        $splittedField = preg_split('/\./', $field);
        if (count($splittedField) == 1){
            return \in_array($this->entity() . '.'.$field, $this->getAllowedFields());
        }
        return \in_array($field, $this->getAllowedFields());  

    }

    // @Override
    public function entity(): string {
        return DB_PREFIX.'scores';
    }

    // @Override
    public function getAllowedOrderFields(): array {
        return [
            DB_PREFIX.'scores.id',
            DB_PREFIX.'scores.name',
            DB_PREFIX.'scores.century',
            DB_PREFIX.'cupboards.name',
            DB_PREFIX.'cupboards.id',
            DB_PREFIX.'boxes.name', 
            DB_PREFIX.'boxes.id', 
            DB_PREFIX.'composers.name',
            DB_PREFIX.'composers.id',
            DB_PREFIX.'styles.name',
            DB_PREFIX.'styles.id',
            DB_PREFIX.'languages.name',
            DB_PREFIX.'languages.id',
            DB_PREFIX.'lyricists.name',
            DB_PREFIX.'lyricists.id',
            DB_PREFIX.'choirTypes.name',
            DB_PREFIX.'choirTypes.id'
        ];
    }

    private function getAllowedFields(): array {
        return [
            'scores.id',
            'scores.name',
            'scores.century',
            'cupboards.name',
            'cupboards.id',
            'boxes.name', 
            'boxes.id', 
            'composers.name',
            'composers.id',
            'styles.name',
            'styles.id',
            'languages.name',
            'languages.id',
            'lyricists.name',
            'lyricists.id',
            'choirTypes.name',
            'choirTypes.id'
        ];
    }

    // @Override
    public function jsonSerialize() {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'century' => $this->century,
            'cupboard' => $this->cupboard,
            'box' => $this->box,
            'composer' => $this->composer,
            'style' => $this->style,
            'language' => $this->language,
            'lyricist' => $this->lyricist,
            'choirType' => $this->choirType
        ];
    }
}