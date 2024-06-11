<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\FloatType;
use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class PiFunction extends CallFunctionOperation {
    public const NAME = 'pi';

    public int $args = 0;

    public function call(array $args): NumberType {
        return new FloatType(pi());
    }
}
