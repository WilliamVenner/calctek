<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

interface PolyadicToken {
    public function as_unary(): UnaryOpToken;
    public function as_binary(): BinOpToken;
}
