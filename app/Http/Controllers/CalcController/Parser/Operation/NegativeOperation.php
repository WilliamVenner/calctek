<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\HasPrecedence;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\PrefixUnaryOperatorPrecedence;

class NegativeOperation extends Operation implements PrefixUnaryOperation, HasPrecedence {
    use PrefixUnaryOperatorPrecedence;

    public function execute(NumberType $operand): NumberType
    {
        $operand->value = -$operand->value;
        return $operand;
    }
}
