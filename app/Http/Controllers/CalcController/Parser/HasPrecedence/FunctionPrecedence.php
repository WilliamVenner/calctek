<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

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
