<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class FactorialToken extends UnaryOpToken {
    public const SYMBOL = '!';

    public function evaluate(NumberToken $operand) {
        $result = 1;
        for ($i = 1; $i <= $operand->value; $i++) {
            $result *= $i;
        }
        return new NumberToken($result);
    }
}
