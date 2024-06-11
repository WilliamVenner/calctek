<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class PowerToken extends SymbolToken implements BinOpToken {
    public const SYMBOL = '^';

    public function precedence(): int {
        return 4;
    }

    public function left_assoc(): bool {
        return false;
    }

    public function evaluate(NumberToken $left, NumberToken $right) {
        return $left->value ** $right->value;
    }
}
