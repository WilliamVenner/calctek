<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class WordToken extends Token {
    public string $word;

    public function __construct(string $word) {
        $this->word = $word;
    }
}
