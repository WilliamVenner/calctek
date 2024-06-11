<?php

namespace App\Http\Controllers\CalcController\Evaluator\EvaluatorType;

use App\Http\Controllers\CalcController\NamedCalcClass;

/**
 * A type of value that can be pushed onto the stack during evaluation.
 */
abstract class EvaluatorType {
    use NamedCalcClass;
}
