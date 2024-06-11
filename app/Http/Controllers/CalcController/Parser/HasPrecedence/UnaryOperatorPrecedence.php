<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

/**
 * Precedence for unary operators.
 */
trait UnaryOperatorPrecedence {
    public function precedence(): int {
        return 5;
    }

    // left_associative() is not defined here; see PostfixUnaryOperatorPrecedence and PrefixUnaryOperatorPrecedence
}
