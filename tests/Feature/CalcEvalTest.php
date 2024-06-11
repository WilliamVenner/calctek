<?php

namespace Tests\Feature;

use Tests\ExactTestCase;

/**
 * Miscellaneous tests for the calculator's mathematical expression evaluator.
 */
class CalcEvalTest extends ExactTestCase
{
    public function test_calculator_can_ignore_whitespace()
    {
        $response = $this->get('/calc/eval/' . rawurlencode("  1 + 2\n+\t3+\r4  "));
        $response->assertStatus(200);
        $response->assertSeeTextExact('10');
    }

    public function test_calculator_can_evaluate_basic_expression()
    {
        $response = $this->get('/calc/eval/' . rawurlencode('1+2+3+4+5+6'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('21');
    }

    public function test_calculator_rejects_invalid_expression()
    {
        $response = $this->get('/calc/eval/' . rawurlencode('1+2+3#+4+5+6'));
        $response->assertStatus(400);
        $response->assertSeeTextExact('Unexpected token at "#+4+5+6"', false);
    }

    public function test_calculator_rejects_imbalanced_operator()
    {
        $response = $this->get('/calc/eval/' . rawurlencode('1+2+'));
        $response->assertStatus(400);
        $response->assertSeeTextExact('Invalid operand while evaluating BinaryOperation (IntegerType, null)', false);

        $response = $this->get('/calc/eval/' . rawurlencode('1-2-'));
        $response->assertStatus(400);
        $response->assertSeeTextExact('Invalid operand while evaluating BinaryOperation (IntegerType, null)', false);
    }

    public function test_calculator_rejects_postfix()
    {
        // One limitation of the Shunting-Yard algorithm is that it can errorneously accept postfix expressions.
        // We've adapted our lexer and parser to reject this.

        $response = $this->get('/calc/eval/' . rawurlencode('1 + 3'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('4');

        $response = $this->get('/calc/eval/' . rawurlencode('1 3'));
        $response->assertStatus(400);

        $response = $this->get('/calc/eval/' . rawurlencode('1 3 +'));
        $response->assertStatus(400);
        $response->assertSeeTextExact('A number token cannot precede another number token', false);

        $response = $this->get('/calc/eval/' . rawurlencode('+ 1 3'));
        $response->assertStatus(400);
        $response->assertSeeTextExact('A number token cannot precede another number token', false);
    }

    public function test_calculator_rejects_postfix_function_calls()
    {
        $response = $this->get('/calc/eval/' . rawurlencode('max(5,10) + min(5,10)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('15');

        $response = $this->get('/calc/eval/' . rawurlencode('max min(5,10)'));
        $response->assertStatus(400);
        $response->assertSeeTextExact('Encountered a function call with missing parentheses', false);

        $response = $this->get('/calc/eval/' . rawurlencode('max(5,10) min(5,10)'));
        $response->assertStatus(400);
        $response->assertSeeTextExact('Invalid token WordToken after a closed parenthesis', false);

        $response = $this->get('/calc/eval/' . rawurlencode('max(5,10) min(5,10) +'));
        $response->assertStatus(400);
        $response->assertSeeTextExact('Invalid token WordToken after a closed parenthesis', false);
    }

    public function test_calculator_can_evaluate_unary_op() {
        $response = $this->get('/calc/eval/' . rawurlencode('5!'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('120');

        $response = $this->get('/calc/eval/' . rawurlencode('5!+5'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('125');
    }

    public function test_calculator_can_evaluate_chained_unary_op() {
        $response = $this->get('/calc/eval/' . rawurlencode('5!%'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('1.2');

        $response = $this->get('/calc/eval/' . rawurlencode('5!%+5'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('6.2');
    }

    public function test_calculator_rejects_invalid_unary_op()
    {
        $response = $this->get('/calc/eval/' . rawurlencode('!'));
        $response->assertStatus(400);

        $response = $this->get('/calc/eval/' . rawurlencode('!5'));
        $response->assertStatus(400);

        $response = $this->get('/calc/eval/' . rawurlencode(')!5'));
        $response->assertStatus(400);
    }

    public function test_calculator_order_of_operations()
    {
        // PHP doesn't have unary operators like ! (factorial) or % (percent)
        // We'll just pre-calculate them and use the results in this test
        $five_factorial = 120; // 5!
        $ten_percent = 0.1; // 10%

        $expected = max((1+2*3**4/$five_factorial), ((6+7*8**fmod(9/$ten_percent,$five_factorial))/2)**3);
        $calc_expr = 'max((1+2*3^4/5!), ((6+7*8^mod(9/10%,5!))/2)^3)';

        $response = $this->get('/calc/eval/' . rawurlencode($calc_expr));
        $response->assertStatus(200);
        $response->assertTextStartsWith(strval($expected));
    }

    public function test_calculator_rejects_imbalanced_parentheses()
    {
        $response = $this->get('/calc/eval/' . rawurlencode(')5'));
        $response->assertStatus(400);
    }

    public function test_calculator_tolerates_imbalanced_commas()
    {
        $response = $this->get('/calc/eval/' . rawurlencode(',,max(,,-5,,30,,),,'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('30');
    }

    public function test_calculator_eval_rejects_unary_around_function_name() {
        $response = $this->get('/calc/eval/' . rawurlencode('ceil-5'));
        $response->assertStatus(400);

        $response = $this->get('/calc/eval/' . rawurlencode('ceil 5!'));
        $response->assertStatus(400);

        $response = $this->get('/calc/eval/' . rawurlencode('5! ceil'));
        $response->assertStatus(400);

        $response = $this->get('/calc/eval/' . rawurlencode('!5 ceil'));
        $response->assertStatus(400);
    }

    public function test_calculator_eval_rejects_function_call_after_function_name() {
        $response = $this->get('/calc/eval/' . rawurlencode('ceil ceil(5)'));
        $response->assertStatus(400);
    }

    public function test_calculator_eval_rejects_sole_unary_operator() {
        $response = $this->get('/calc/eval/' . rawurlencode('-'));
        $response->assertStatus(400);

        $response = $this->get('/calc/eval/' . rawurlencode('!'));
        $response->assertStatus(400);
    }
}
