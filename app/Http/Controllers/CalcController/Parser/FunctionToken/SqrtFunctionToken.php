<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class SqrtFunctionToken extends FunctionToken {
    public const NAME = 'sqrt';

    public int $args = 1;

    public function evaluate($args) {
        return sqrt($args[0]->value);
    }
}
