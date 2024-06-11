<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class DegFunction extends CallFunctionOperation {
    public const NAME = 'deg';

    public int $args = 1;

    public function call(array $args): NumberType {
        return NumberType::from_mixed(rad2deg($args[0]->value));
    }
}
