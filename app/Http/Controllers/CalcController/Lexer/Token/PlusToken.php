<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\AddOperation;
use App\Http\Controllers\CalcController\Parser\Operation\PositiveOperation;

class PlusToken extends Token implements PrefixUnaryOperatorToken, BinaryOperatorToken, PolyadicOperatorToken {
    public function unary_operation_class(): string
    {
        return PositiveOperation::class;
    }

    public function binary_operation_class(): string
    {
        return AddOperation::class;
    }
}
