<?php

namespace App\Ensemblers;

use App\Models\Score;
use App\Payloads\ScorePayload;


class ScoreEnsembler {

    public function ensemble(Score $score): ScorePayload {
        $payload = new ScorePayload();
        $payload->id = $score->getValue('id');
        $payload->name = $score->getValue('name');
        $payload->century = $score->getValue('century');
        $payload->cupboard = $score->getValue('cupboard');
        $payload->box = $score->getValue('box');
        $payload->composer = $score->getValue('composer');
        $payload->style = $score->getValue('style');
        $payload->language = $score->getValue('language');
        $payload->lyricist = $score->getValue('lyricist');
        $payload->choirType = $score->getValue('choirType');
        return $payload;
    }
}
