<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\FloatType;
use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\IntegerType;
use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class ModFunction extends CallFunctionOperation {
    public const NAME = 'mod';

    public int $args = 2;

    public function call(array $args): NumberType {
        if ($args[0] instanceof FloatType || $args[1] instanceof FloatType) {
            return new FloatType(fmod($args[0]->value, $args[1]->value));
        } else {
            return new IntegerType($args[0]->value % $args[1]->value);
        }
    }
}
