<?php

namespace App\Http\Controllers\CalcController\Evaluator\EvaluatorType;

use TypeError;

/**
 * A number value.
 */
abstract class NumberType extends EvaluatorType {
    public mixed $value;

    protected function __construct(mixed $value) {
        $this->value = $value;
    }

    /**
     * Instantiate either a FloatType or an IntegerType based on the PHP type of the given value.
     *
     * @param int|float $value The value to create the NumberType instance from.
     * @return NumberType
     */
    public static function from_mixed(mixed $value): NumberType {
        if (is_int($value)) {
            return new IntegerType($value);
        } else if (is_float($value)) {
            return new FloatType($value);
        } else {
            throw new TypeError("Invalid type for NumberType");
        }
    }
}
