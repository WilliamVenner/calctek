<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class ModuloToken extends BinOpToken {
    public string $symbol = '%';
    public int $precedence = 3;
    public bool $left_assoc = true;

    public function evaluate(NumberToken $left, NumberToken $right) {
        return $left->value % $right->value;
    }
}
