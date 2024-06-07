<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class ParenthesisToken extends SymbolToken {
    public int $precedence = 0;
}
