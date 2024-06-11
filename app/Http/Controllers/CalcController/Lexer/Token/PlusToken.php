<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\AddOperation;
use App\Http\Controllers\CalcController\Parser\Operation\BinaryOperation;
use App\Http\Controllers\CalcController\Parser\Operation\PositiveOperation;
use App\Http\Controllers\CalcController\Parser\Operation\UnaryOperation;

class PlusToken extends Token implements PrefixUnaryOperatorToken, BinaryOperatorToken, PolyadicOperatorToken {
    public function as_unary_operation(): UnaryOperation
    {
        return new PositiveOperation();
    }

    public function as_binary_operation(): BinaryOperation
    {
        return new AddOperation();
    }
}
