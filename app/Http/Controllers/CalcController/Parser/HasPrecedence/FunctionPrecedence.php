<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

/**
 * Function call precedence.
 */
trait FunctionPrecedence {
    public function precedence(): int
    {
        return 0;
    }

    public function left_associative(): bool
    {
        return false;
    }
}
