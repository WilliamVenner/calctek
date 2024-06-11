<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\FloatType;
use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\IntegerType;
use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;
use App\Http\Controllers\CalcController\Lexer\Token\FloatToken;

class RandFunction extends CallFunctionOperation {
    public const NAME = 'rand';

    public int $args = 2;

    public function call(array $args): NumberType {
        $min = $args[0]->value;
        $max = $args[1]->value;
        $rand = mt_rand() / mt_getrandmax();
        $n = $min + ($rand * ($max - $min));
        if ($args[0] instanceof FloatToken || $args[1] instanceof FloatToken) {
            return new FloatType($n);
        } else {
            return new IntegerType((int)round($n));
        }
    }
}
