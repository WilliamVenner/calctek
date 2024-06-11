<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;

/**
 * Interface for unary operations (operations that take one operand)
 */
interface UnaryOperation {
    /**
     * Executes the unary operation on the given operand.
     *
     * @param NumberType $operand The operand on which the unary operation is performed.
     * @return NumberType The result of the unary operation.
     */
    public function execute(NumberType $operand): NumberType;
}
