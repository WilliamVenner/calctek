<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

trait MulDivPrecedence {
    public function precedence(): int
    {
        return 3;
    }

    public function left_associative(): bool
    {
        return true;
    }
}
