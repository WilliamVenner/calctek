<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\MulOperation;

class MulToken extends Token implements BinaryOperatorToken, OperatorToken {
    public function operation_class(): string
    {
        return MulOperation::class;
    }
}
