<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;
use App\Http\Controllers\CalcController\NamedCalcClass;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\FunctionPrecedence;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\HasPrecedence;
use App\Http\Controllers\CalcController\Parser\ParserException;

/**
 * A "call function" operation
 */
abstract class CallFunctionOperation implements HasPrecedence {
    use FunctionPrecedence, NamedCalcClass;

    /**
     * Map of function names to their classes.
     */
    const FUNCTIONS = [
        MaxFunction::NAME => MaxFunction::class,
        MinFunction::NAME => MinFunction::class,
        SinFunction::NAME => SinFunction::class,
        CosFunction::NAME => CosFunction::class,
        TanFunction::NAME => TanFunction::class,
        AbsFunction::NAME => AbsFunction::class,
        SqrtFunction::NAME => SqrtFunction::class,
        LogFunction::NAME => LogFunction::class,
        CeilFunction::NAME => CeilFunction::class,
        FloorFunction::NAME => FloorFunction::class,
        RoundFunction::NAME => RoundFunction::class,
        RandFunction::NAME => RandFunction::class,
        PiFunction::NAME => PiFunction::class,
        EFunction::NAME => EFunction::class,
        ModFunction::NAME => ModFunction::class,
        DegFunction::NAME => DegFunction::class,
        RadFunction::NAME => RadFunction::class,
    ];

    /**
     * The name of this function.
     */
    public const NAME = '<undefined>';

    /**
     * The number of arguments this function takes.
     */
    public int $args;

    /**
     * Look up the given function using its name, returning a CallFunctionOperation instance.
     *
     * @param string $name The name of the function.
     * @return CallFunctionOperation The created CallFunctionOperation instance.
     */
    public static function from_name(string $name) {
        $funcClass = self::FUNCTIONS[$name] ?? null; // Get the class for this function
        if ($funcClass !== null) {
            return new $funcClass($name);
        } else {
            throw new ParserException("Unknown function: $name", 400);
        }
    }

    /**
     * Call the function with the given arguments.
     *
     * @param array $args Any arguments this function takes, popped from the stack.
     * @return NumberType The result of the function call.
     */
    public abstract function call(array $args): NumberType;
}
