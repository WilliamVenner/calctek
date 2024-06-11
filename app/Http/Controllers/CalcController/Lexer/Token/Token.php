<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use ReflectionClass;

abstract class Token {
    public function token_name(): string {
        return (new ReflectionClass($this))->getShortName();
    }
}
