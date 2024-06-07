<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class FloorFunctionToken extends FunctionToken {
    public const NAME = 'floor';

    public int $args = 1;

    public function evaluate($args) {
        return floor($args[0]->value);
    }
}
