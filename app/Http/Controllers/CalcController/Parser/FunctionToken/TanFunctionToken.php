<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class TanFunctionToken extends FunctionToken {
    public const NAME = 'tan';

    public int $args = 1;

    public function evaluate($args) {
        return tan($args[0]->value);
    }
}
