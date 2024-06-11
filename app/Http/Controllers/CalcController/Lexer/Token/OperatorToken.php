<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

use App\Http\Controllers\CalcController\Parser\Operation\Operation;

interface OperatorToken {
    public function as_operation(): Operation;
}
