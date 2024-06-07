<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class MaxFunctionToken extends FunctionToken {
    public const NAME = 'max';

    public int $args = 2;

    public function evaluate($args) {
        return max($args[0]->value, $args[1]->value);
    }
}
