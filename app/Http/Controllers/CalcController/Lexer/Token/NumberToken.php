<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

abstract class NumberToken extends Token {
    public mixed $value;

    public function __construct(mixed $value) {
        $this->value = $value;
    }
}
