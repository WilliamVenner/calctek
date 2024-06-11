<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\FloatType;
use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class SqrtFunction extends CallFunctionOperation {
    public const NAME = 'sqrt';

    public int $args = 1;

    public function call(array $args): NumberType {
        return new FloatType(sqrt($args[0]->value));
    }
}
