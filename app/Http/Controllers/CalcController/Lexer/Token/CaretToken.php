<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\PowerOperation;

class CaretToken extends Token implements BinaryOperatorToken, OperatorToken {
    public function operation_class(): string
    {
        return PowerOperation::class;
    }
}
