<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

use App\Http\Controllers\CalcController\Lexer\Token\WordToken;
use App\Http\Controllers\CalcController\Parser\ParserException;

abstract class FunctionToken extends WordToken {
    const FUNCTIONS = [
        MaxFunctionToken::NAME => MaxFunctionToken::class,
        MinFunctionToken::NAME => MinFunctionToken::class,
        SinFunctionToken::NAME => SinFunctionToken::class,
        CosFunctionToken::NAME => CosFunctionToken::class,
        TanFunctionToken::NAME => TanFunctionToken::class,
        AbsFunctionToken::NAME => AbsFunctionToken::class,
        SqrtFunctionToken::NAME => SqrtFunctionToken::class,
        LogFunctionToken::NAME => LogFunctionToken::class,
        CeilFunctionToken::NAME => CeilFunctionToken::class,
        FloorFunctionToken::NAME => FloorFunctionToken::class,
        RoundFunctionToken::NAME => RoundFunctionToken::class,
        RandFunctionToken::NAME => RandFunctionToken::class,
        PiFunctionToken::NAME => PiFunctionToken::class,
        EFunctionToken::NAME => EFunctionToken::class,
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

    public abstract function evaluate($args);
}
