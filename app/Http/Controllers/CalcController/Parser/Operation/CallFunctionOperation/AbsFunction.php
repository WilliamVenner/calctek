<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class AbsFunction extends CallFunctionOperation {
    public const NAME = 'abs';

    public int $args = 1;

    public function call(array $args): NumberType {
        return NumberType::from_mixed(abs($args[0]->value));
    }
}
