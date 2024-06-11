<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

/**
 * A sequence of characters that is not an operator or a number.
 */
class WordToken extends Token {
    public string $word;

    public function __construct(string $word) {
        $this->word = $word;
    }
}
