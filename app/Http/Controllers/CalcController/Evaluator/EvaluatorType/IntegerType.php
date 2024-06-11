<?php

namespace App\Http\Controllers\CalcController\Evaluator\EvaluatorType;

class IntegerType extends NumberType {
    public function __construct(int $value) {
        parent::__construct($value);
    }
}
