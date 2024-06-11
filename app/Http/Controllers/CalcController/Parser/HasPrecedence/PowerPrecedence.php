<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

/**
 * Precedence for the power operator.
 */
trait PowerPrecedence {
    public function precedence(): int
    {
        return 4;
    }

    public function left_associative(): bool
    {
        return false;
    }
}
