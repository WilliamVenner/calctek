<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class MultiplyToken extends SymbolToken implements BinOpToken {
    public const SYMBOL = '*';

    public function precedence(): int {
        return 3;
    }

    public function left_assoc(): bool {
        return true;
    }

    public function evaluate(NumberToken $left, NumberToken $right) {
        return $left->value * $right->value;
    }
}
