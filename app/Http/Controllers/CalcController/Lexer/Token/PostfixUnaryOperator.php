<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

trait PostfixUnaryOperator {
    use UnaryOperator;

    public function left_assoc(): bool {
        return true;
    }
}
