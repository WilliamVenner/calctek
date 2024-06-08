<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class PercentToken extends UnaryOpToken {
    public const SYMBOL = '%';

    public function evaluate(NumberToken $operand) {
        return $operand->value / 100.0;
    }
}
