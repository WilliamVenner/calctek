<?php

namespace App\Http\Controllers\CalcController\Evaluator;

use App\Http\Controllers\CalcController\Lexer\Token\NumberToken;
use App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation\CallFunctionOperation;
use App\Http\Controllers\CalcController\Evaluator\EvaluatorType\NumberType;
use App\Http\Controllers\CalcController\Parser\Operation\BinaryOperation;
use App\Http\Controllers\CalcController\Parser\Operation\PushNumberOperation;
use App\Http\Controllers\CalcController\Parser\Operation\PushOperation;
use App\Http\Controllers\CalcController\Parser\Operation\UnaryOperation;

class Evaluator {
    private array $operator_stack;
    private array $output_queue;

    public function __construct(array $output_queue) {
        $this->operator_stack = [];
        $this->output_queue = $output_queue;
    }

    public function evaluate() {
        foreach ($this->output_queue as $token) {
            if ($token instanceof PushOperation) {
                $token->execute($this->operator_stack);
            } else if ($token instanceof BinaryOperation) {
                $op2 = array_pop($this->operator_stack);
                $op1 = array_pop($this->operator_stack);

                if (!($op2 instanceof NumberType) || !($op1 instanceof NumberType)) {
                    throw new EvaluatorException('Invalid operand while evaluating BinaryOperation (' . ($op2 ? $op2->name() : 'null') . ', ' . ($op1 ? $op1->name() : 'null') . ')', 400);
                }

                $this->operator_stack[] = $token->execute($op1, $op2);
            } else if ($token instanceof UnaryOperation) {
                $operand = array_pop($this->operator_stack);
                if (!($operand instanceof NumberType)) {
                    throw new EvaluatorException('Invalid operand while evaluating UnaryOperation (' . ($operand ? $operand->name() : 'null') . ')', 400);
                }
                $this->operator_stack[] = $token->execute($operand);
            } else if ($token instanceof CallFunctionOperation) {
                if (count($this->operator_stack) < $token->args) {
                    throw new EvaluatorException('Expected ' . $token->args . ' argument(s) for function ' . $token->name() . ' (got ' . count($this->operator_stack) . ')', 400);
                } else {
                    $args = array_splice($this->operator_stack, count($this->operator_stack) - $token->args, $token->args);
                    $this->operator_stack[] = $token->call($args);
                }
            } else {
                throw new EvaluatorException('Invalid operation encountered during evaluation (' . $token->name() . ')', 400);
            }
        }

        if (count($this->operator_stack) !== 1 || !($this->operator_stack[0] instanceof NumberType)) {
            throw new EvaluatorException('Invalid number of operands left after evaluation', 400);
        }

        return $this->operator_stack[0]->value;
    }
}
