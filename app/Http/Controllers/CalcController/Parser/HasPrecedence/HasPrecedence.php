<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

interface HasPrecedence {
    public function precedence(): int;
    public function left_associative(): bool;
}
