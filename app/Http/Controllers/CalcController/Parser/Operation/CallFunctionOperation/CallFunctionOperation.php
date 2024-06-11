<?php

namespace App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation;

use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;
use App\Http\Controllers\CalcController\NamedCalcClass;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\FunctionPrecedence;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\HasPrecedence;
use App\Http\Controllers\CalcController\Parser\ParserException;

abstract class CallFunctionOperation implements HasPrecedence {
    use FunctionPrecedence, NamedCalcClass;

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

    public const NAME = '<undefined>';
    public int $args;

    public static function from_name(string $name) {
        $funcClass = self::FUNCTIONS[$name] ?? null; // Get the class for this function
        if ($funcClass !== null) {
            return new $funcClass($name);
        } else {
            throw new ParserException("Unknown function: $name", 400);
        }
    }

    public abstract function call(array $args): NumberType;
}
