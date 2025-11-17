<?php

namespace App\Models;

use App\Application\Orm\Model;

class Score extends Model {

    public $entity = 'scores';

    public int $id;
    public string $name;
    public int $century;
    public ?Cupboard $cupboard;
    public ?Box $box;
    public ?Composer $composer;
    public ?Style $style;
    public ?Language $language;
    public ?Lyricist $lyricist;
    public ?ChoirType $choirType;

    public $attributes = [
        'id' => ['type' => 'int', 'primary' => true],
        'name' => ['type' => 'string'],
        'century' => ['type' => 'int'],
        'cupboard' => ['type' => 'App\Models\Cupboard', 'relation' => 'cupboardId'],
        'box' => ['type' => 'App\Models\Box', 'relation' => 'boxId'],
        'composer' => ['type' => 'App\Models\Composer', 'relation' => 'composerId'],
        'style' => ['type' => 'App\Models\Style', 'relation' => 'styleId'],
        'language' => ['type' => 'App\Models\Language', 'relation' => 'languageId'],
        'lyricist' => ['type' => 'App\Models\Lyricist', 'relation' => 'lyricistId'],
        'choirType' => ['type' => 'App\Models\ChoirType', 'relation' => 'choirTypeId']
    ];

    public function getAttributes(): array {
        return $this->attributes;
    }

    public function validateFilterField(string $key): bool {
        return array_search($key, $this->getAllowedFilterFields());
    }

    // @Override
    public function getAllowedOrderFields(): array {
        return [
            'id',
            'name',
            'century',
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

    private function getAllowedFilterFields(): array {
        return [
            'id', 'name', 'name__icontains' ,'century', 'century__icontains',
            'composer.id', 'lyricist.id', 'style.id', 'language.id', 'choirType.id',
            'cupboard.id', 'box.id'
        ];
    }

    // @Override
    public function jsonSerialize(): mixed {
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