<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class FloatToken extends NumberToken {
    public function __construct(float $value) {
        parent::__construct($value);
    }
}
