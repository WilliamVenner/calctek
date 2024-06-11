<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

/**
 * Add (+) and subtract (-) operation precedence.
 */
trait AddSubPrecedence {
    public function precedence(): int
    {
        return 2;
    }

    public function left_associative(): bool
    {
        return true;
    }
}
