<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

use App\Http\Controllers\CalcController\Lexer\Token\FloatToken;

class ModFunctionToken extends FunctionToken {
    public const NAME = 'mod';

    public int $args = 2;

    public function evaluate($args) {
        if ($args[0] instanceof FloatToken || $args[1] instanceof FloatToken) {
            return fmod($args[0]->value, $args[1]->value);
        } else {
            return $args[0]->value % $args[1]->value;
        }
    }
}

class ModuloFunctionToken extends ModFunctionToken {
    public const NAME = 'modulo';
}
