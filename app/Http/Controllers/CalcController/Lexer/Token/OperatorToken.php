<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

interface OperatorToken {
    public function operation_class(): string;
}
