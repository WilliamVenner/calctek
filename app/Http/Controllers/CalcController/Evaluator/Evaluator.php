<?php

namespace App\Http\Controllers\CalcController\Evaluator;

use App\Http\Controllers\CalcController\Lexer\Token\BinOpToken;
use App\Http\Controllers\CalcController\Lexer\Token\NumberToken;
use App\Http\Controllers\CalcController\Lexer\Token\UnaryOpToken;
use App\Http\Controllers\CalcController\Parser\FunctionToken\FunctionToken;

class Evaluator {
    private array $operator_stack;
    private array $output_queue;

    public function __construct(array $output_queue) {
        $this->operator_stack = [];
        $this->output_queue = $output_queue;
    }

    public function evaluate() {
        foreach ($this->output_queue as $token) {
            if ($token instanceof NumberToken) {
                $this->operator_stack[] = $token;
            } else if ($token instanceof BinOpToken) {
                $op2 = array_pop($this->operator_stack);
                $op1 = array_pop($this->operator_stack);

                if (!($op2 instanceof NumberToken) || !($op1 instanceof NumberToken)) {
                    throw new EvaluatorException('Invalid operand while evaluating BinOpToken (' . ($op2 ? $op2->token_name() : 'null') . ', ' . ($op1 ? $op1->token_name() : 'null') . ')', 400);
                }

                $this->operator_stack[] = NumberToken::from_mixed($token->evaluate($op1, $op2));
            } else if ($token instanceof UnaryOpToken) {
                $operand = array_pop($this->operator_stack);
                if (!($operand instanceof NumberToken)) {
                    throw new EvaluatorException('Invalid operand while evaluating UnaryOpToken (' . ($operand ? $operand->token_name() : 'null') . ')', 400);
                }
                $this->operator_stack[] = NumberToken::from_mixed($token->evaluate($operand));
            } else if ($token instanceof FunctionToken) {
                if (count($this->operator_stack) < $token->args) {
                    throw new EvaluatorException('Expected ' . $token->args . ' argument(s) for function ' . $token->value . ' (got ' . count($this->operator_stack) . ')', 400);
                } else {
                    $args = array_splice($this->operator_stack, count($this->operator_stack) - $token->args, $token->args);
                    $this->operator_stack[] = NumberToken::from_mixed($token->evaluate($args));
                }
            } else {
                throw new EvaluatorException('Invalid token encountered during evaluation (' . $token->token_name() . ')', 400);
            }
        }

        if (count($this->operator_stack) !== 1 || !($this->operator_stack[0] instanceof NumberToken)) {
            throw new EvaluatorException('Invalid number of operands left after evaluation', 400);
        }

        return $this->operator_stack[0]->value;
    }
}
