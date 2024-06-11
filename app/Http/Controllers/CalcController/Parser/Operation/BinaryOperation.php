<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

/**
 * Interface for binary operations (operations that take two operands)
 */
interface BinaryOperation {
    public function execute(NumberType $op1, NumberType $op2): NumberType;
}
