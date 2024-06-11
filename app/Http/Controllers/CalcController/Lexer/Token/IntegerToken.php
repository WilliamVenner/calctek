<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class IntegerToken extends NumberToken {
    public function __construct(int $value) {
        parent::__construct($value);
    }
}
