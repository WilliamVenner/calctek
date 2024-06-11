<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

trait UnaryOperator {
    public function precedence(): int {
        return 5;
    }

    public function left_assoc(): bool {
        return true;
    }
}
