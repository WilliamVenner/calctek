<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\MulOperation;
use App\Http\Controllers\CalcController\Parser\Operation\Operation;

class MulToken extends Token implements BinaryOperatorToken, OperatorToken {
    public function as_operation(): Operation
    {
        return new MulOperation();
    }
}
