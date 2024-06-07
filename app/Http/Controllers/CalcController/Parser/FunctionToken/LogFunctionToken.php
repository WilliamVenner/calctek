<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class LogFunctionToken extends FunctionToken {
    public const NAME = 'log';

    public int $args = 2;

    public function evaluate($args) {
        return log($args[0]->value, $args[1]->value);
    }
}
