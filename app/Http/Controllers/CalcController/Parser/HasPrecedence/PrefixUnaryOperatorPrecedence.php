<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

/**
 * Precedence for prefix unary operators (e.g. unary negative).
 */
trait PrefixUnaryOperatorPrecedence {
    use UnaryOperatorPrecedence;

    public function left_associative(): bool {
        return false;
    }
}
