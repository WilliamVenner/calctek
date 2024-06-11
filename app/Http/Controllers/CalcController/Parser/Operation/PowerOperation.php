<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\PowerPrecedence;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\HasPrecedence;

class PowerOperation extends Operation implements BinaryOperation, HasPrecedence {
    use PowerPrecedence;

    public function execute(NumberType $op1, NumberType $op2): NumberType
    {
        return NumberType::from_mixed($op1->value ** $op2->value);
    }
}
