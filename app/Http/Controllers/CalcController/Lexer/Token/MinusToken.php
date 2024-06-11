<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\SubOperation;
use App\Http\Controllers\CalcController\Parser\Operation\NegativeOperation;

class MinusToken extends Token implements PrefixUnaryOperatorToken, BinaryOperatorToken, PolyadicOperatorToken {
    public function unary_operation_class(): string
    {
        return NegativeOperation::class;
    }

    public function binary_operation_class(): string
    {
        return SubOperation::class;
    }
}
