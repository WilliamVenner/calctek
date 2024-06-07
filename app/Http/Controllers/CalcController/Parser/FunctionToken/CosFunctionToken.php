<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class CosFunctionToken extends FunctionToken {
    public const NAME = 'cos';

    public int $args = 1;

    public function evaluate($args) {
        return cos($args[0]->value);
    }
}
