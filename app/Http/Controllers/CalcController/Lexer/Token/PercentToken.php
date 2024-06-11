<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\PercentOperation;

class PercentToken extends Token implements PostfixUnaryOperatorToken, OperatorToken {
    public function operation_class(): string
    {
        return PercentOperation::class;
    }
}
