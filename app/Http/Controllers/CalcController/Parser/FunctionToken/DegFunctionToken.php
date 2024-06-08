<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class DegFunctionToken extends FunctionToken {
    public const NAME = 'deg';

    public int $args = 1;

    public function evaluate($args) {
        return rad2deg($args[0]->value);
    }
}
