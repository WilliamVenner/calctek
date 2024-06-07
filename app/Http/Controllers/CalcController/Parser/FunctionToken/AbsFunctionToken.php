<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class AbsFunctionToken extends FunctionToken {
    public const NAME = 'abs';

    public int $args = 1;

    public function evaluate($args) {
        return abs($args[0]->value);
    }
}
