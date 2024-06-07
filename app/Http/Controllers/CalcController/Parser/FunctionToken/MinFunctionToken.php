<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class MinFunctionToken extends FunctionToken {
    public const NAME = 'min';

    public int $args = 2;

    public function evaluate($args) {
        return min($args[0]->value, $args[1]->value);
    }
}
