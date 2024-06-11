<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\FloatType;
use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class RadFunction extends CallFunctionOperation {
    public const NAME = 'rad';

    public int $args = 1;

    public function call(array $args): NumberType {
        return new FloatType(deg2rad($args[0]->value));
    }
}
