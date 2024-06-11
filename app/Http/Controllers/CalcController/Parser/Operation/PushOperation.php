<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\EvaluatorType;

abstract class PushOperation extends Operation {
    protected mixed $value;

    public function __construct(EvaluatorType $value) {
        $this->value = $value;
    }

    public function execute(array &$stack) {
        $stack[] = $this->value;
    }
}
