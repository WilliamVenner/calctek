<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class WordToken extends Token implements OpToken {
    public string $value;

    public function precedence(): int {
        return 1;
    }

    public function left_assoc(): bool {
        return true;
    }

    public function __construct(string $value) {
        $this->value = $value;
    }
}
