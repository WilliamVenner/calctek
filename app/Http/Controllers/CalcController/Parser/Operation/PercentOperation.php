<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\FloatType;
use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\HasPrecedence;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\PostfixUnaryOperatorPrecedence;

class PercentOperation extends Operation implements PostfixUnaryOperation, HasPrecedence {
    use PostfixUnaryOperatorPrecedence;

    public function execute(NumberType $operand): NumberType
    {
        return new FloatType((float)$operand->value / 100);
    }
}
