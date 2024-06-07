<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class WordToken extends Token {
    public string $value;
    public int $precedence = 1;

    public function __construct(string $value) {
        $this->value = $value;
    }
}
