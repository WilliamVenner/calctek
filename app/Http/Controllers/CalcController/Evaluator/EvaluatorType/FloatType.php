<?php

namespace App\Http\Controllers\CalcController\Evaluator\EvaluatorType;

/**
 * A floating point number.
 */
class FloatType extends NumberType {
    public function __construct(float $value) {
        parent::__construct($value);
    }
}
