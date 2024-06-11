<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\FloatType;
use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class TanFunction extends CallFunctionOperation {
    public const NAME = 'tan';

    public int $args = 1;

    public function call(array $args): NumberType {
        return new FloatType(tan($args[0]->value));
    }
}
