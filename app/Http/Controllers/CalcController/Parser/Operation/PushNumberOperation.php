<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

class PushNumberOperation extends PushOperation {
    public function __construct(mixed $value) {
        parent::__construct(NumberType::from_mixed($value));
    }
}
