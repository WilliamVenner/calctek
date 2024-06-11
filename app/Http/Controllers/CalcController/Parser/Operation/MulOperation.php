<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\HasPrecedence;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\MulDivPrecedence;

class MulOperation extends Operation implements BinaryOperation, HasPrecedence {
    use MulDivPrecedence;

    public function execute(NumberType $op1, NumberType $op2): NumberType
    {
        return NumberType::from_mixed($op1->value * $op2->value);
    }
}
