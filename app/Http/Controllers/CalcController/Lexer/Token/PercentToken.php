<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\PercentOperation;
use App\Http\Controllers\CalcController\Parser\Operation\Operation;

class PercentToken extends Token implements PostfixUnaryOperatorToken, OperatorToken {
    public function as_operation(): Operation
    {
        return new PercentOperation();
    }
}
