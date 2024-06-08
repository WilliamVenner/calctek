<?php

namespace App\Http\Controllers\CalcController\Parser;

use App\Http\Controllers\CalcController\Evaluator\Evaluator;
use App\Http\Controllers\CalcController\Lexer\Token\ClosedParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\CommaToken;
use App\Http\Controllers\CalcController\Lexer\Token\NumberToken;
use App\Http\Controllers\CalcController\Lexer\Token\OpenParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\OpToken;
use App\Http\Controllers\CalcController\Lexer\Token\ParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\WordToken;
use App\Http\Controllers\CalcController\Parser\FunctionToken\FunctionToken;

class Parser {
    private array $output_queue;
    private array $operator_stack;

    // Shunting-Yard algorithm
    // https://en.wikipedia.org/wiki/Shunting_yard_algorithm#The_algorithm_in_detail
    public function infix_to_rpn(array $tokens): Evaluator {
        $tokens = array_reverse($tokens);

        $this->output_queue = [];
        $this->operator_stack = [];

        while ($token = array_pop($tokens)) {
            if ($token instanceof NumberToken) {
                $this->output_queue[] = $token;
            } else if ($token instanceof WordToken) {
                $this->operator_stack[] = FunctionToken::from_name($token->value);
            } else if ($token instanceof OpToken) {
                while ($operator = end($this->operator_stack)) {
                    if (!($operator instanceof OpenParenthesisToken) && ($operator->precedence > $token->precedence || ($token->precedence === $operator->precedence && $token->left_assoc))) {
                        $this->output_queue[] = array_pop($this->operator_stack);
                    } else {
                        break;
                    }
                }
                $this->operator_stack[] = $token;
            } else if ($token instanceof CommaToken) {
                while ($operator = end($this->operator_stack)) {
                    if (!($operator instanceof OpenParenthesisToken)) {
                        $this->output_queue[] = array_pop($this->operator_stack);
                    } else {
                        break;
                    }
                }
            } else if ($token instanceof OpenParenthesisToken) {
                $this->operator_stack[] = $token;
            } else if ($token instanceof ClosedParenthesisToken) {
                while ($operator = end($this->operator_stack)) {
                    if (!($operator instanceof OpenParenthesisToken)) {
                        if (!empty($this->operator_stack)) {
                            $this->output_queue[] = array_pop($this->operator_stack);
                        } else {
                            throw new ParserException('Mismatched parentheses', 400);
                        }
                    } else {
                        break;
                    }
                }

                $pop = array_pop($this->operator_stack);
                assert($pop instanceof OpenParenthesisToken);

                if ($func = end($this->operator_stack)) {
                    if ($func instanceof WordToken) {
                        $this->output_queue[] = array_pop($this->operator_stack);
                    }
                }
            } else {
                throw new ParserException('Unexpected token ' . get_class($token), 400);
            }
        }

        while ($operator = array_pop($this->operator_stack)) {
            if ($operator instanceof ParenthesisToken) {
                throw new ParserException('Mismatched parentheses (at end of stack)', 400);
            }

            $this->output_queue[] = $operator;
        }

        return new Evaluator($this->operator_stack, $this->output_queue);
    }
}
