<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

trait PostfixUnaryOperatorPrecedence {
    use UnaryOperatorPrecedence;

    public function left_associative(): bool {
        return true;
    }
}
