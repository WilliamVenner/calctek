<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

abstract class UnaryOpToken extends OpToken {
    public abstract function evaluate(NumberToken $operand);
}
