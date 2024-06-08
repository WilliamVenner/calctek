<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

abstract class OpToken extends SymbolToken {
    public int $precedence;
    public bool $left_assoc;
}
