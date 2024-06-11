<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

trait PrefixUnaryOperator {
    use UnaryOperator;

    public function left_assoc(): bool {
        return false;
    }
}
