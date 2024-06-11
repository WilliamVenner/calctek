<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\BinaryOperation;
use App\Http\Controllers\CalcController\Parser\Operation\SubOperation;
use App\Http\Controllers\CalcController\Parser\Operation\NegativeOperation;
use App\Http\Controllers\CalcController\Parser\Operation\UnaryOperation;

class MinusToken extends Token implements PrefixUnaryOperatorToken, BinaryOperatorToken, PolyadicOperatorToken {
    public function as_unary_operation(): UnaryOperation
    {
        return new NegativeOperation();
    }

    public function as_binary_operation(): BinaryOperation
    {
        return new SubOperation();
    }
}
