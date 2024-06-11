<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\NamedCalcClass;

/**
 * Tokens that are produced by the Lexer.
 */
abstract class Token {
    use NamedCalcClass;
}
