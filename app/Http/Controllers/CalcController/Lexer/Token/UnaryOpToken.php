<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

abstract class UnaryOpToken extends OpToken {
    public int $precedence = 1;
    public bool $left_assoc = true;

    public abstract function evaluate(NumberToken $operand);
}
