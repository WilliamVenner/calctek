<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class SinFunctionToken extends FunctionToken {
    public const NAME = 'sin';

    public int $args = 1;

    public function evaluate($args) {
        return sin($args[0]->value);
    }
}
