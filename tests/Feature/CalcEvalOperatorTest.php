<?php

namespace Tests\Feature;

use Tests\ExactTestCase;

class CalcEvalOperatorTests extends ExactTestCase
{
    public function test_calculator_eval_can_add_integers() {
        $response = $this->get('/calc/eval/' . rawurlencode('1+2'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('3');
    }

    public function test_calculator_eval_can_add_floats() {
        $response = $this->get('/calc/eval/' . rawurlencode('1.5+2.5'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('4');
    }

    public function test_calculator_eval_can_sub_integers() {
        $response = $this->get('/calc/eval/' . rawurlencode('1-2'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-1');
    }

    public function test_calculator_eval_can_sub_floats() {
        $response = $this->get('/calc/eval/' . rawurlencode('1.5-5.5'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-4');
    }

    public function test_calculator_eval_can_mul_integers() {
        $response = $this->get('/calc/eval/' . rawurlencode('2*3'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('6');
    }

    public function test_calculator_eval_can_mul_floats() {
        $response = $this->get('/calc/eval/' . rawurlencode('2.5*3.5'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('8.75');
    }

    public function test_calculator_eval_can_div_integers() {
        $response = $this->get('/calc/eval/' . rawurlencode('6/3'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('2');
    }

    public function test_calculator_eval_can_div_floats() {
        $response = $this->get('/calc/eval/' . rawurlencode('7.5/2.5'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('3');
    }

    public function test_calculator_eval_can_pow_integers() {
        $response = $this->get('/calc/eval/' . rawurlencode('6^3'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('216');
    }

    public function test_calculator_eval_can_pow_floats() {
        $response = $this->get('/calc/eval/' . rawurlencode('7.5^2.5'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('154.046969');
    }

    public function test_calculator_eval_can_factorial_integers() {
        $response = $this->get('/calc/eval/' . rawurlencode('5!'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('120');
    }

    public function test_calculator_eval_can_factorial_floats() {
        $response = $this->get('/calc/eval/' . rawurlencode('1.1!'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('1.0464858');
    }

    public function test_calculator_eval_can_percent_integers() {
        $response = $this->get('/calc/eval/' . rawurlencode('5%'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('0.05');
    }

    public function test_calculator_eval_can_percent_floats() {
        $response = $this->get('/calc/eval/' . rawurlencode('5.5%'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('0.055');
    }

    public function test_calculator_eval_can_handle_sole_negative_number() {
        $response = $this->get('/calc/eval/' . rawurlencode('-5'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-5');
    }

    public function test_calculator_eval_can_handle_sole_positive_number() {
        $response = $this->get('/calc/eval/' . rawurlencode('+5'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('5');
    }

    public function test_calculator_eval_can_handle_scientific_notation() {
        $response = $this->get('/calc/eval/' . rawurlencode('4.76108854e14'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('4.76108854E+14');

        $response = $this->get('/calc/eval/' . rawurlencode('478854e14'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('4.78854E+19');

        $response = $this->get('/calc/eval/' . rawurlencode('476108854e-14'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('4.76108854E-6');

        $response = $this->get('/calc/eval/' . rawurlencode('+4.76108854e14'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('4.76108854E+14');

        $response = $this->get('/calc/eval/' . rawurlencode('-4.76108854e14'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-4.76108854E+14');

        $response = $this->get('/calc/eval/' . rawurlencode('+476108854e14'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('4.76108854E+22');

        $response = $this->get('/calc/eval/' . rawurlencode('-476108854e14'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-4.76108854E+22');
    }

    public function test_calculator_eval_can_handle_chained_subtraction() {
        // This test is intended to test the behaviour of the lexer:
        // are the numbers recognised as positive numbers being subtracted, or are they recognised as negative numbers?
        $response = $this->get('/calc/eval/' . rawurlencode('1-2-3-4-5-6'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-19');
    }

    public function test_calculator_eval_can_handle_chained_addition() {
        // This test is intended to test the behaviour of the lexer:
        // are the numbers recognised as positive numbers being added, or are they recognised as positive numbers?
        $response = $this->get('/calc/eval/' . rawurlencode('1+2+3+4+5+6'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('21');
    }

    public function test_calculator_eval_can_handle_negation_expression() {
        $response = $this->get('/calc/eval/' . rawurlencode('-(1+2+3+4+5+6)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-21');
    }

    public function test_calculator_eval_can_handle_chained_negation_expression() {
        $response = $this->get('/calc/eval/' . rawurlencode('----(1+2+3+4+5+6)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('21');
    }

    public function test_calculator_eval_can_handle_chained_negative_negation() {
        $response = $this->get('/calc/eval/' . rawurlencode('-1 - -2 - -3 - -4 - -5 - -6'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('19');
    }

    public function test_calculator_eval_can_handle_binary_binop_expression() {
        $response = $this->get('/calc/eval/' . rawurlencode('(5+5)-(5+5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('0');

        $response = $this->get('/calc/eval/' . rawurlencode('5+5-5+5'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('10');
    }
}
