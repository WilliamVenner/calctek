<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class MinusToken extends SymbolToken implements PolyadicToken {
    public const SYMBOL = '-';

    public function as_unary(): UnaryOpToken {
        return new NegativeToken();
    }

    public function as_binary(): BinOpToken {
        return new SubtractToken();
    }
}
