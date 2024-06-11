<?php

namespace App\Http\Controllers\CalcController\Parser;

use App\Http\Controllers\CalcController\Evaluator\Evaluator;
use App\Http\Controllers\CalcController\Lexer\Token\BinOpToken;
use App\Http\Controllers\CalcController\Lexer\Token\ClosedParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\CommaToken;
use App\Http\Controllers\CalcController\Lexer\Token\NumberToken;
use App\Http\Controllers\CalcController\Lexer\Token\OpenParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\OpToken;
use App\Http\Controllers\CalcController\Lexer\Token\ParenthesisToken;
use App\Http\Controllers\CalcController\Lexer\Token\PolyadicToken;
use App\Http\Controllers\CalcController\Lexer\Token\PostfixUnaryOpToken;
use App\Http\Controllers\CalcController\Lexer\Token\Token;
use App\Http\Controllers\CalcController\Lexer\Token\UnaryOpToken;
use App\Http\Controllers\CalcController\Lexer\Token\WordToken;
use App\Http\Controllers\CalcController\Parser\FunctionToken\FunctionToken;

class Parser
{
    private int $i = 0;
    private array $tokens;

    private array $output_queue;
    private array $operator_stack;

    // Shunting-Yard algorithm
    // https://en.wikipedia.org/wiki/Shunting_yard_algorithm#The_algorithm_in_detail
    public function infix_to_rpn(array $tokens): Evaluator
    {
        $this->tokens = array_reverse($tokens);
        $this->i = count($this->tokens) - 1;
        $this->output_queue = [];
        $this->operator_stack = [];

        while ($token = &$this->pop_token()) {
            if ($token instanceof PolyadicToken) {
                if ($this->expect_prev_token([OpenParenthesisToken::class, BinOpToken::class, PolyadicToken::class, CommaToken::class], true)) {
                    $token = $token->as_unary();
                } else {
                    $token = $token->as_binary();
                }
            }

            if ($token instanceof NumberToken) {
                if ($this->prev_token() instanceof NumberToken) {
                    throw new ParserException('A number token cannot precede another number token', 400);
                }

                $this->output_queue[] = $token;
            } else if ($token instanceof WordToken) {
                if (!($this->peek_token() instanceof OpenParenthesisToken)) {
                    throw new ParserException('Encountered a function call with missing parentheses', 400);
                }

                $this->operator_stack[] = FunctionToken::from_name($token->value);
            } else if ($token instanceof CommaToken) {
                // TODO assert here
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
                // TODO assert here
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

                // A closed parenthesis should always be followed by a comma or an operator
                if (!$this->expect_next_token([ClosedParenthesisToken::class, CommaToken::class, BinOpToken::class, UnaryOpToken::class, PolyadicToken::class], true)) {
                    throw new ParserException('Invalid token ' . ($this->peek_token()?->token_name() ?? 'null') . ' after a closed parenthesis', 400);
                }
            } else if ($token instanceof OpToken) {
                if ($token instanceof PostfixUnaryOpToken) {
                    if (empty($this->output_queue) || !$this->expect_prev_token([NumberToken::class, ClosedParenthesisToken::class, UnaryOpToken::class])) {
                        throw new ParserException('Invalid operand ' . ($this->prev_token()?->token_name() ?? 'null') . ' while evaluating ' . $token->token_name(), 400);
                    }
                }

                while ($operator = end($this->operator_stack)) {
                    if (!($operator instanceof OpenParenthesisToken) && ($operator->precedence() > $token->precedence() || ($token->precedence() === $operator->precedence() && $token->left_assoc()))) {
                        $this->output_queue[] = array_pop($this->operator_stack);
                    } else {
                        break;
                    }
                }
                $this->operator_stack[] = $token;
            } else {
                throw new ParserException('Unexpected token ' . $token->token_name(), 400);
            }

            //var_dump([ "stack" => $this->operator_stack, "queue" => $this->output_queue ]);
        }

        while ($operator = array_pop($this->operator_stack)) {
            if ($operator instanceof ParenthesisToken) {
                throw new ParserException('Mismatched parentheses (at end of stack)', 400);
            }

            $this->output_queue[] = $operator;
        }

        return new Evaluator($this->output_queue);
    }

    private function &pop_token(): ?Token
    {
        $token = $this->tokens[$this->i] ?? null;
        $this->i--;
        return $token;
    }

    private function peek_token(): ?Token
    {
        return $this->tokens[$this->i] ?? null;
    }

    private function prev_token(): ?Token
    {
        return $this->tokens[$this->i + 2] ?? null;
    }

    private function expect_next_token(mixed $expecting, bool $allow_null = false): bool
    {
        return static::expect_token($this->peek_token(), $expecting, $allow_null);
    }

    private function expect_prev_token(mixed $expecting, bool $allow_null = false): bool
    {
        return static::expect_token($this->prev_token(), $expecting, $allow_null);
    }

    private static function expect_token(?Token $token, mixed $expecting, bool $allow_null = false): bool
    {
        if ($token) {
            if (is_array($expecting)) {
                foreach ($expecting as $expect) {
                    if ($token instanceof $expect) {
                        return true;
                    }
                }
            } else if ($token instanceof $expecting) {
                return true;
            }
        } else if ($allow_null) {
            return true;
        }

        return false;
    }
}
