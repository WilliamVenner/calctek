<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class CeilFunctionToken extends FunctionToken {
    public const NAME = 'ceil';

    public int $args = 1;

    public function evaluate($args) {
        return ceil($args[0]->value);
    }
}
