<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

/**
 * Euler's number (e) function
 */
class EFunction extends CallFunctionOperation {
    public const NAME = 'e';

    public int $args = 0;

    public function call(array $args): NumberType {
        return NumberType::from_mixed(exp(1));
    }
}
