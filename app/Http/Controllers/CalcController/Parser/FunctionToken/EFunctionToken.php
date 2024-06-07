<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class EFunctionToken extends FunctionToken {
    public const NAME = 'e';

    public int $args = 0;

    public function evaluate($args) {
        return exp(1);
    }
}
