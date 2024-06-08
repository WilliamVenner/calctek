<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

abstract class BinOpToken extends OpToken {
    public abstract function evaluate(NumberToken $left, NumberToken $right);
}
