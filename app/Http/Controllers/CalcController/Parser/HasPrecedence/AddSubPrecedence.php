<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

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
