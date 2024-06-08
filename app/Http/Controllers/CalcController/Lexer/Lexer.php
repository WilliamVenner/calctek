<?php

namespace App\Http\Controllers\CalcController\Lexer;

use App\Http\Controllers\CalcController\Lexer\Token\AddToken;
use App\Http\Controllers\CalcController\Lexer\Token\SubtractToken;
use App\Http\Controllers\CalcController\Lexer\Token\MultiplyToken;
use App\Http\Controllers\CalcController\Lexer\Token\DivideToken;
use App\Http\Controllers\CalcController\Lexer\Token\PowerToken;
use App\Http\Controllers\CalcController\Lexer\Token\ModuloToken;
use App\Http\Controllers\CalcController\Lexer\Token\FactorialToken;
use App\Http\Controllers\CalcController\Lexer\Token\OpenParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\ClosedParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\IntegerToken;
use App\Http\Controllers\CalcController\Lexer\Token\FloatToken;
use App\Http\Controllers\CalcController\Lexer\Token\WordToken;
use App\Http\Controllers\CalcController\Lexer\Token\CommaToken;

class Lexer {
    const OPERATORS = [
        AddToken::SYMBOL => AddToken::class,
        SubtractToken::SYMBOL => SubtractToken::class,
        MultiplyToken::SYMBOL => MultiplyToken::class,
        DivideToken::SYMBOL => DivideToken::class,
        PowerToken::SYMBOL => PowerToken::class,
        ModuloToken::SYMBOL => ModuloToken::class,
        FactorialToken::SYMBOL => FactorialToken::class,
        ClosedParenthesisToken::SYMBOL => ClosedParenthesisToken::class,
        OpenParenthesisToken::SYMBOL => OpenParenthesisToken::class,
        DivideToken::SYMBOL => DivideToken::class,

        '÷' => DivideToken::class,
        '⨯' => MultiplyToken::class,
    ];

    const RE_TOKEN = '/((?:-|\+)?(?:\d+)(?:\.(?:\d*))?(?:(?:E|e)(?:\+|-)(?:\d+))?)|(\w+)/SA'; // TODO split into two regexes
    const RE_TOKEN_GROUP_NUMBER = 1;
    const RE_TOKEN_GROUP_WORD = 2;

    public function lex(string $expr): array {
        $tokens = [];

        $expr = preg_replace('/\s+/', '', $expr); // Remove all whitespace

        $offset = 0;
        while ($offset < strlen($expr)) {
            $char = mb_substr($expr, $offset, 1); // Extract the first character

            // First, let's check if this character is one of our operators
            {
                $operator = self::OPERATORS[$char] ?? null; // Get the class for this operator
                if ($operator !== null) {
                    // We've found an operator token!
                    $tokens[] = new $operator($char);
                    $offset += strlen($char);
                    continue;
                }
            }

            // Next, let's try to match either a number or a word
            $matches = [];
            $result = preg_match(self::RE_TOKEN, $expr, $matches, 0, $offset);
            if ($result === false) {
                throw new LexerException('Internal error while lexing (RE_TOKEN)', 500);
            } else if ($result === 0) {
                // We've ALLEGEDLY reached the end of the expression
                break;
            }

            // We've found a number or a word.
            $number = $matches[self::RE_TOKEN_GROUP_NUMBER];
            if ($number !== '') {
                // Let's check if PHP can parse this number, first
                if (!is_numeric($number)) {
                    throw new LexerException('Internal error while lexing (unparseable number "' . $number . '")', 500);
                } else {
                    // If it's an integer, we'll use an IntegerToken, otherwise a FloatToken
                    $number = $number + 0; // HACK! Adding zero to a string will convert it to an int OR float for us
                    $tokens[] = is_int($number) ? new IntegerToken((int)$number) : new FloatToken((float)$number);
                }
            } else {
                // It's a word
                $word = $matches[self::RE_TOKEN_GROUP_WORD];
                $tokens[] = new WordToken($word);
            }

            $offset += strlen($matches[0]);
        }

        // If there's still tokens left, then there's an error
        if ($offset < strlen($expr)) {
            throw new LexerException('Unexpected token at "' . substr($expr, $offset) . '"', 400);
        }

        return $tokens;
    }
}