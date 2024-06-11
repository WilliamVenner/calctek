<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class SubtractToken extends MinusToken implements BinOpToken {
    public function precedence(): int {
        return 2;
    }

    public function left_assoc(): bool {
        return true;
    }

    public function evaluate(NumberToken $left, NumberToken $right) {
        return $left->value - $right->value;
    }
}
