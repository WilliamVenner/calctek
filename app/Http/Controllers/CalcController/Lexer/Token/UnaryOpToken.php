<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

interface UnaryOpToken extends OpToken {
    public function evaluate(NumberToken $operand);
}
