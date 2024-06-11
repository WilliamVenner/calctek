<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class CeilFunction extends CallFunctionOperation {
    public const NAME = 'ceil';

    public int $args = 1;

    public function call(array $args): NumberType {
        return NumberType::from_mixed(ceil($args[0]->value));
    }
}
