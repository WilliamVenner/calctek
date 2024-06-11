<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\DivideOperation;
use App\Http\Controllers\CalcController\Parser\Operation\Operation;

class DivideToken extends Token implements BinaryOperatorToken, OperatorToken {
    public function as_operation(): Operation
    {
        return new DivideOperation();
    }
}
