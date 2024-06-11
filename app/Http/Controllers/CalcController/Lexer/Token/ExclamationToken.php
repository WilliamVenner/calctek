<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\FactorialOperation;

class ExclamationToken extends Token implements PostfixUnaryOperatorToken, OperatorToken {
    public function operation_class(): string
    {
        return FactorialOperation::class;
    }
}
