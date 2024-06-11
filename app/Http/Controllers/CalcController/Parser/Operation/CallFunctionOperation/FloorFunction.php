<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class FloorFunction extends CallFunctionOperation {
    public const NAME = 'floor';

    public int $args = 1;

    public function call(array $args): NumberType {
        return NumberType::from_mixed(floor($args[0]->value));
    }
}
