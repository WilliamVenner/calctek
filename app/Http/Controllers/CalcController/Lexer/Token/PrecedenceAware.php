<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

interface PrecedenceAware {
    public function precedence(): int;
    public function left_assoc(): bool;
}
