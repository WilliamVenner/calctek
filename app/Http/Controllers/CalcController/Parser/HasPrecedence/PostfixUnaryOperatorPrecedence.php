<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

/**
 * Precedence for postfix unary operators (e.g. factorial).
 */
trait PostfixUnaryOperatorPrecedence {
    use UnaryOperatorPrecedence;

    public function left_associative(): bool {
        return true;
    }
}
