<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

use App\Http\Controllers\CalcController\Lexer\Token\FloatToken;

class RandFunctionToken extends FunctionToken {
    public const NAME = 'rand';

    public int $args = 2;

    public function evaluate($args) {
        $min = $args[0]->value;
        $max = $args[1]->value;
        $rand = mt_rand() / mt_getrandmax();
        $n = $min + ($rand * ($max - $min));
        if ($args[0] instanceof FloatToken || $args[1] instanceof FloatToken) {
            return $n;
        } else {
            return (int)round($n);
        }
    }
}
