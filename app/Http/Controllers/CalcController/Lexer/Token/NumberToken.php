<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use TypeError;

abstract class NumberToken extends Token {
    public $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public static function from_mixed(mixed $value) {
        if (is_int($value)) {
            return new IntegerToken($value);
        } else if (is_float($value)) {
            return new FloatToken($value);
        } else {
            throw new TypeError('Invalid type for NumberToken: ' . gettype($value));
        }
    }
}
