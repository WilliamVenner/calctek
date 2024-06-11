<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class MaxFunction extends CallFunctionOperation {
    public const NAME = 'max';

    public int $args = 2;

    public function call(array $args): NumberType {
        return NumberType::from_mixed(max($args[0]->value, $args[1]->value));
    }
}
