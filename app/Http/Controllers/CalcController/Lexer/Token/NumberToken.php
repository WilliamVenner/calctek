<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

/**
 * A token representing a number. Can be an integer or a float.
 */
abstract class NumberToken extends Token {
    public mixed $value;

    public function __construct(mixed $value) {
        $this->value = $value;
    }
}
