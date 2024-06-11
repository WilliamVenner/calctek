<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class CosFunction extends CallFunctionOperation {
    public const NAME = 'cos';

    public int $args = 1;

    public function call(array $args): NumberType {
        return NumberType::from_mixed(cos($args[0]->value));
    }
}
