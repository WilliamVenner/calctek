<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class RandFunctionToken extends FunctionToken {
    public const NAME = 'rand';

    public int $args = 2;

    public function evaluate($args) {
        $min = $args[0]->value;
        $max = $args[1]->value;
        $rand = mt_rand() / mt_getrandmax();
        $n = $min + ($rand * ($max - $min));
        if (is_float($min) || is_float($max)) {
            return $n;
        } else {
            return (int)($n);
        }
    }
}
