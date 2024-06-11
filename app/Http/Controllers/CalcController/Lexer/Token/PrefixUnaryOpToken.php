<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

interface PrefixUnaryOpToken extends UnaryOpToken {
    public function evaluate(NumberToken $operand);
}
