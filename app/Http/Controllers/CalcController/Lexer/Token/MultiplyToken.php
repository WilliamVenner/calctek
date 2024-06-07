<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class MultiplyToken extends BinOpToken {
    public const SYMBOL = '*';
    public int $precedence = 3;
    public bool $left_assoc = true;

    public function evaluate(NumberToken $left, NumberToken $right) {
        return $left->value * $right->value;
    }
}
