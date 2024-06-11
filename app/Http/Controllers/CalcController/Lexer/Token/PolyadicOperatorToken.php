<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\BinaryOperation;
use App\Http\Controllers\CalcController\Parser\Operation\UnaryOperation;

/**
 * A polyadic operator is an operator that can be used as both a unary or binary operator.
 *
 * For example, the minus operator (-) can be used as a unary operator to negate a number, or as a binary operator to subtract two numbers; this makes it polyadic.
 */
interface PolyadicOperatorToken {
    /**
     * Instantiate the associated operation for the unary form of this operator.
     *
     * @return UnaryOperation The associated unary operation for this operator
     */
    public function as_unary_operation(): UnaryOperation;

    /**
     * Instantiate the associated operation for the binary form of this operator.
     *
     * @return UnaryOperation The associated binary operation for this operator
     */
    public function as_binary_operation(): BinaryOperation;
}
