<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

abstract class BinOpToken extends OpToken {
    public bool $left_assoc;

    public abstract function evaluate(NumberToken $left, NumberToken $right);
}
