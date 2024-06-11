<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

class NegativeToken extends MinusToken implements PrefixUnaryOpToken {
    use PrefixUnaryOperator;

    public function evaluate(NumberToken $operand) {
        return -$operand->value;
    }
}
