<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class ModFunctionToken extends FunctionToken {
    public const NAME = 'mod';

    public int $args = 2;

    public function evaluate($args) {
        return $args[0]->value % $args[1]->value;
    }
}

class ModuloFunctionToken extends ModFunctionToken {
    public const NAME = 'modulo';
}
