<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

interface UnaryOperation {
    public function execute(NumberType $operand): NumberType;
}
