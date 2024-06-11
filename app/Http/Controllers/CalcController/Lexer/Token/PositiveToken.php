<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class PositiveToken extends MinusToken implements PrefixUnaryOpToken {
    use PrefixUnaryOperator;

    public function evaluate(NumberToken $operand) {
        // no-op
        return $operand->value;
    }
}
