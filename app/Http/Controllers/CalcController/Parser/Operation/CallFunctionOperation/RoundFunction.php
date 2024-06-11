<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\IntegerType;
use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class RoundFunction extends CallFunctionOperation {
    public const NAME = 'round';

    public int $args = 1;

    public function call(array $args): NumberType {
        return new IntegerType(round($args[0]->value));
    }
}
