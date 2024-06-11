<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class ParenthesisToken extends SymbolToken implements PrecedenceAware {
    public function precedence(): int {
        return 0;
    }

    public function left_assoc(): bool {
        return false;
    }
}
