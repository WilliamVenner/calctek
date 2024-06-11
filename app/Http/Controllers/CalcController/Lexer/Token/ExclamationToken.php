<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\FactorialOperation;
use App\Http\Controllers\CalcController\Parser\Operation\Operation;

class ExclamationToken extends Token implements PostfixUnaryOperatorToken, OperatorToken {
    public function as_operation(): Operation
    {
        return new FactorialOperation();
    }
}
