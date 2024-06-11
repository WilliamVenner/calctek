<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

trait PrefixUnaryOperatorPrecedence {
    use UnaryOperatorPrecedence;

    public function left_associative(): bool {
        return false;
    }
}
