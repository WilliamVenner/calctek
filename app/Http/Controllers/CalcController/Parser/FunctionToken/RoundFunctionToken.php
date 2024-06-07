<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class RoundFunctionToken extends FunctionToken {
    public const NAME = 'round';

    public int $args = 1;

    public function evaluate($args) {
        return round($args[0]->value);
    }
}
