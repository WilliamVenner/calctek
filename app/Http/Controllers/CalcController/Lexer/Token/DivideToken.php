<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\DivideOperation;

class DivideToken extends Token implements BinaryOperatorToken, OperatorToken {
    public function operation_class(): string
    {
        return DivideOperation::class;
    }
}
