<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

interface PolyadicOperatorToken {
    public function unary_operation_class(): string;
    public function binary_operation_class(): string;
}
