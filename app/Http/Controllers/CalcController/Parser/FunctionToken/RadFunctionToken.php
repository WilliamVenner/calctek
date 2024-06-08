<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class RadFunctionToken extends FunctionToken {
    public const NAME = 'rad';

    public int $args = 1;

    public function evaluate($args) {
        return deg2rad($args[0]->value);
    }
}
