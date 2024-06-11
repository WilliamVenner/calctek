<?php

namespace App\Http\Controllers\CalcController\Lexer;

use App\Http\Controllers\CalcController\Lexer\Token\CaretToken;
use App\Http\Controllers\CalcController\Lexer\Token\DivideToken;
use App\Http\Controllers\CalcController\Lexer\Token\PercentToken;
use App\Http\Controllers\CalcController\Lexer\Token\OpenParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\CloseParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\IntegerToken;
use App\Http\Controllers\CalcController\Lexer\Token\FloatToken;
use App\Http\Controllers\CalcController\Lexer\Token\WordToken;
use App\Http\Controllers\CalcController\Lexer\Token\CommaToken;
use App\Http\Controllers\CalcController\Lexer\Token\ExclamationToken;
use App\Http\Controllers\CalcController\Lexer\Token\MinusToken;
use App\Http\Controllers\CalcController\Lexer\Token\MulToken;
use App\Http\Controllers\CalcController\Lexer\Token\PlusToken;

/**
 * Lexes a mathematical expression into tokens.
 */
class Lexer {
    /**
     * Regular expression to match PHP numbers OR words (e.g. function identifiers)
     */
    const RE_TOKEN = '/((?:\d+)(?:\.(?:\d*))?(?:(?:E|e)(?:\+|-)?(?:\d+))?)|(\w+)/SA';
    const RE_TOKEN_GROUP_NUMBER = 1;
    const RE_TOKEN_GROUP_WORD = 2;

    /**
     * Lex the given expression into tokens.
     *
     * @param string $expr The expression to lex.
     * @return array The tokens in the expression.
     */
    public function lex(string $expr): array {
        $tokens = [];

        $offset = 0;
        while ($offset < strlen($expr)) {
            // Extract the first character
            // Note that we use mb_substr(substr(...)) here:
            // This is because preg_* functions will use byte offsets, not multibyte character offsets
            // So, we substring the string to the current offset, and then use mb_substr to get the first character
            $char = mb_substr(substr($expr, $offset), 0, 1);

            if (ctype_space($char)) {
                // Skip whitespace
                $offset += strlen($char);
                continue;
            }

            // Let's check if this character is one of our operators
            $operator = null;
            switch ($char) {
                case '+':
                    $operator = new PlusToken();
                    break;

                case '-':
                    $operator = new MinusToken();
                    break;

                case '*':
                case '⨯':
                    $operator = new MulToken();
                    break;

                case '/':
                case '÷':
                    $operator = new DivideToken();
                    break;

                case '^':
                    $operator = new CaretToken();
                    break;

                case '%':
                    $operator = new PercentToken();
                    break;

                case '!':
                    $operator = new ExclamationToken();
                    break;

                case '(':
                    $operator = new OpenParenthesisToken();
                    break;

                case ')':
                    $operator = new CloseParenthesisToken();
                    break;

                case ',':
                    $operator = new CommaToken();
                    break;
            }
            if ($operator !== null) {
                // We've found an operator token!
                $tokens[] = $operator;
                $offset += strlen($char);
                continue;
            }

            // Let's try to match either a number or a word
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
