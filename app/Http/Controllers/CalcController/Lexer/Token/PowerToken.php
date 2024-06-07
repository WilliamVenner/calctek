<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class PowerToken extends BinOpToken {
    public const SYMBOL = '^';
    public int $precedence = 4;
    public bool $left_assoc = false;

    public function evaluate(NumberToken $left, NumberToken $right) {
        return $left->value ** $right->value;
    }
}
