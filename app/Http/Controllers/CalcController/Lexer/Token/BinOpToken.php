<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

interface BinOpToken extends OpToken {
    public function evaluate(NumberToken $left, NumberToken $right);
}
