<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\PowerOperation;
use App\Http\Controllers\CalcController\Parser\Operation\Operation;

class CaretToken extends Token implements BinaryOperatorToken, OperatorToken {
    public function as_operation(): Operation
    {
        return new PowerOperation();
    }
}
