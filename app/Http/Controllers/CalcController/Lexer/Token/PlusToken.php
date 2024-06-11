<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class PlusToken extends SymbolToken implements PolyadicToken {
    public const SYMBOL = '+';

    public function as_unary(): UnaryOpToken {
        return new PositiveToken();
    }

    public function as_binary(): BinOpToken {
        return new AddToken();
    }
}
