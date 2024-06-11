<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

interface PostfixUnaryOpToken extends UnaryOpToken {
    public function evaluate(NumberToken $operand);
}
