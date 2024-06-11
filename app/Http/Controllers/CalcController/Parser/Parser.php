<?php

namespace App\Http\Controllers\CalcController\Parser;

use App\Http\Controllers\CalcController\Parser\Operation\CallFunctionOperation\CallFunctionOperation;
use App\Http\Controllers\CalcController\Evaluator\Evaluator;
use App\Http\Controllers\CalcController\Lexer\Token\BinaryOperatorToken;
use App\Http\Controllers\CalcController\Lexer\Token\CloseParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\CommaToken;
use App\Http\Controllers\CalcController\Lexer\Token\NumberToken;
use App\Http\Controllers\CalcController\Lexer\Token\OpenParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\OperatorToken;
use App\Http\Controllers\CalcController\Lexer\Token\ParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\PolyadicOperatorToken;
use App\Http\Controllers\CalcController\Lexer\Token\Token;
use App\Http\Controllers\CalcController\Lexer\Token\UnaryOperatorToken;
use App\Http\Controllers\CalcController\Lexer\Token\WordToken;
use App\Http\Controllers\CalcController\Parser\HasPrecedence\HasPrecedence;
use App\Http\Controllers\CalcController\Parser\Operation\PushNumberOperation;
use App\Http\Controllers\CalcController\Parser\Operation\Operation;
use App\Http\Controllers\CalcController\Parser\Operation\PostfixUnaryOperation;

/**
 * Parses a queue of Evaluator operations from a stack of Lexer tokens.
 */
class Parser
{
    /**
     * The stack of tokens from the Lexer that we are parsing.
     */
    private array $tokens;

    /**
     * The current position of the cursor in the token stack
     *
     * Sometimes the parser has to look-ahead or look-behind, so we need to keep track of the current position..
     *
     * This will be advanced as we pop tokens off the stack.
     */
    private int $i = 0;

    private array $output_queue;
    private array $operator_stack;

    // Shunting-Yard algorithm
    // https://en.wikipedia.org/wiki/Shunting_yard_algorithm#The_algorithm_in_detail
    public function infix_to_rpn(array $tokens): Evaluator
    {
        $this->tokens = array_reverse($tokens); // It's a stack, so reverse the list
        $this->i = count($this->tokens) - 1; // Set the cursor to the end of the stack
        $this->output_queue = [];
        $this->operator_stack = [];

        while ($token = $this->pop_token()) {
            if ($token instanceof NumberToken) {
                if ($this->prev_token() instanceof NumberToken) {
                    throw new ParserException('A number token cannot precede another number token', 400);
                }

                // This is a number, so push it to the output queue
                $this->output_queue[] = new PushNumberOperation($token->value);
                continue;
            } else if ($token instanceof WordToken) {
                if (!($this->peek_token() instanceof OpenParenthesisToken)) {
                    throw new ParserException('Encountered a function call with missing parentheses', 400);
                }

                // This is a function call, so look up which function it is and push it to the operator stack
                $this->operator_stack[] = CallFunctionOperation::from_name($token->word);
                continue;
            } else if ($token instanceof CommaToken) {
                while ($operator = end($this->operator_stack)) {
                    if (!($operator instanceof OpenParenthesisToken)) {
                        $this->output_queue[] = array_pop($this->operator_stack);
                    } else {
                        break;
                    }
                }
                continue;
            } else if ($token instanceof OpenParenthesisToken) {
                $this->operator_stack[] = $token;
                continue;
            } else if ($token instanceof CloseParenthesisToken) {
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
                    if ($func instanceof CallFunctionOperation) {
                        $this->output_queue[] = array_pop($this->operator_stack);
                    }
                }

                // A closed parenthesis should always be followed by a comma or an operator
                if (!$this->lookahead([CloseParenthesisToken::class, CommaToken::class, BinaryOperatorToken::class, PolyadicOperatorToken::class], true)) {
                    throw new ParserException('Invalid token ' . ($this->peek_token()?->name() ?? 'null') . ' after a closed parenthesis', 400);
                }

                continue;
            } else if ($token instanceof OperatorToken || $token instanceof PolyadicOperatorToken) {
                // Operators are polyadic in this calculator implementation.
                // That means, they can either take two operands (binary operation) or one operand (unary operation),
                // BUT - some operators can be BOTH binary operations and unary operations.
                // For example, "-" can be both NegativeOperation (n*-1) or SubOperation (a-b).
                // It only becomes NegativeOperation or SubOperation when considering the context.
                $operation = null;
                if ($token instanceof PolyadicOperatorToken) {
                    // If this token can be a unary or binary operation, we need to look at the previous token to determine which one it is.
                    if ($this->lookbehind([OpenParenthesisToken::class, BinaryOperatorToken::class, PolyadicOperatorToken::class, CommaToken::class], true)) {
                        // If the previous token is an open parenthesis, binary operator, polyadic operator or comma, then this token is a unary operation and takes one operand from its right-hand side.
                        $operation = $token->as_unary_operation();
                    } else {
                        // Otherwise, it's a binary operation and takes two operands.
                        $operation = $token->as_binary_operation();
                    }
                } else if ($token instanceof OperatorToken) {
                    // This is a normal, non-polyadic operator.
                    $operation = $token->as_operation();
                } else {
                    throw new ParserException('Unexpected token ' . $token->name() . ' (not an operator or polyadic operator)', 400);
                }

                // We should have an operation object by now.
                assert($operation instanceof Operation);

                // Reject postfix unary operators if there is no previous token or if the previous token is not a number, close parenthesis or another unary operator.
                // For example, reject "!5"
                if ($operation instanceof PostfixUnaryOperation) {
                    if (empty($this->output_queue) || !$this->lookbehind([NumberToken::class, CloseParenthesisToken::class, UnaryOperatorToken::class])) {
                        throw new ParserException('Invalid operand ' . ($this->prev_token()?->name() ?? 'null') . ' while evaluating ' . $operation->name(), 400);
                    }
                }

                // Order of operations/precedence
                while ($operator = end($this->operator_stack)) {
                    if (!($operator instanceof OpenParenthesisToken)) {
                        if (!($operator instanceof HasPrecedence)) {
                            throw new ParserException('Operator ' . $operator->name() . ' does not have precedence', 400);
                        } else if (!($operation instanceof HasPrecedence)) {
                            throw new ParserException('Operator ' . $operation->name() . ' does not have precedence', 400);
                        } else if ($operator->precedence() > $operation->precedence() || ($operation->precedence() === $operator->precedence() && $operation->left_associative())) {
                            $this->output_queue[] = array_pop($this->operator_stack);
                            continue;
                        }
                    }

                    break;
                }

                $this->operator_stack[] = $operation;
                continue;
            }

            throw new ParserException('Unexpected token ' . $token->name(), 400);
        }

        // If there are any operators left on the stack, pop them to the output queue
        while ($operator = array_pop($this->operator_stack)) {
            if ($operator instanceof ParenthesisToken) {
                throw new ParserException('Mismatched parentheses (at end of stack)', 400);
            }

            $this->output_queue[] = $operator;
        }

        return new Evaluator($this->output_queue);
    }

    /**
     * Pop a token from the token stack and advance the cursor.
     *
     * @return Token|null The popped token, or null if the stack is empty.
     */
    private function pop_token(): ?Token
    {
        $token = $this->tokens[$this->i] ?? null;
        $this->i--;
        return $token;
    }

    /**
     * Peek the token at the top of the token stack.
     *
     * @return Token|null The token at the top of the token stack, or null if the stack is empty.
     */
    private function peek_token(): ?Token
    {
        return $this->tokens[$this->i] ?? null;
    }

    /**
     * Peek the token underneath the top of the token stack.
     *
     * @return Token|null The token underneath the top of the token stack, or null if the stack is empty or has only one element.
     */
    private function prev_token(): ?Token
    {
        return $this->tokens[$this->i + 2] ?? null;
    }

    /**
     * Look ahead at the next token and see if it matches the expected token type.
     *
     * @param mixed $expecting The expected token type fully qualified class names. Can be an array or a single value.
     * @param bool $allow_null Whether to match null tokens.
     * @return bool Whether the next token matches the expected token type.
     */
    private function lookahead(mixed $expecting, bool $allow_null = false): bool
    {
        return static::look($this->peek_token(), $expecting, $allow_null);
    }

    /**
     * Look behind at the previous token and see if it matches the expected token type.
     *
     * @param mixed $expecting The expected token type fully qualified class names. Can be an array or a single value.
     * @param bool $allow_null Whether to match null tokens.
     * @return bool Whether the previous token matches the expected token type.
     */
    private function lookbehind(mixed $expecting, bool $allow_null = false): bool
    {
        return static::look($this->prev_token(), $expecting, $allow_null);
    }

    private static function look(?Token $token, mixed $expecting, bool $allow_null = false): bool
    {
        if ($token) {
            if (is_array($expecting)) {
                // $expecting can be an array of fully qualified class names
                foreach ($expecting as $expect) {
                    if ($token instanceof $expect) {
                        return true;
                    }
                }
            } else if ($token instanceof $expecting) {
                // OR a single fully qualified class name
                return true;
            }
        } else if ($allow_null) {
            // If we allow null tokens, then we can return true if the token is null
            return true;
        }

        // If we didn't find a match, return false
        return false;
    }
}
