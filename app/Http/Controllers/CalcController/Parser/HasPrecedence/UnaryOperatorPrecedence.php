<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

trait UnaryOperatorPrecedence {
    public function precedence(): int {
        return 5;
    }
}
