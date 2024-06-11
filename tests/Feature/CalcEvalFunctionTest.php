<?php

namespace Tests\Feature;

use Tests\ExactTestCase;

/**
 * Tests for calculator function calls (e.g. sin() cos() tan() rand()).
 */
class CalcEvalFunctionTest extends ExactTestCase
{
    public function test_calculator_eval_abs() {
        $response = $this->get('/calc/eval/' . rawurlencode('5'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('5');

        $response = $this->get('/calc/eval/' . rawurlencode('-5'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-5');

        $response = $this->get('/calc/eval/' . rawurlencode('5.555'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('5.555');

        $response = $this->get('/calc/eval/' . rawurlencode('-5.555'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('-5.555');
    }

    public function test_calculator_eval_ceil() {
        $response = $this->get('/calc/eval/' . rawurlencode('ceil(5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('5');

        $response = $this->get('/calc/eval/' . rawurlencode('ceil(5.000000)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('5');

        $response = $this->get('/calc/eval/' . rawurlencode('ceil(5.000001)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('6');

        $response = $this->get('/calc/eval/' . rawurlencode('ceil(5.999999)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('6');

        $response = $this->get('/calc/eval/' . rawurlencode('ceil(-5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-5');

        $response = $this->get('/calc/eval/' . rawurlencode('ceil(-5.000000)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-5');

        $response = $this->get('/calc/eval/' . rawurlencode('ceil(-5.000001)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-5');

        $response = $this->get('/calc/eval/' . rawurlencode('ceil(-5.999999)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-5');
    }

    public function test_calculator_eval_cos() {
        $response = $this->get('/calc/eval/' . rawurlencode('cos(0)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('1');

        $response = $this->get('/calc/eval/' . rawurlencode('cos(1)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('0.540302');

        $response = $this->get('/calc/eval/' . rawurlencode('cos(5.5)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('0.708669774');
    }

    public function test_calculator_eval_deg() {
        $response = $this->get('/calc/eval/' . rawurlencode('deg(1.571)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('90');

        $response = $this->get('/calc/eval/' . rawurlencode('deg(3.142)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('180');
    }

    public function test_calculator_eval_rad() {
        $response = $this->get('/calc/eval/' . rawurlencode('rad(90)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('1.57');

        $response = $this->get('/calc/eval/' . rawurlencode('rad(180)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('3.14');
    }

    public function test_calculator_eval_euler() {
        $response = $this->get('/calc/eval/' . rawurlencode('e()'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('2.71828');
    }

    public function test_calculator_eval_floor() {
        $response = $this->get('/calc/eval/' . rawurlencode('floor(5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('5');

        $response = $this->get('/calc/eval/' . rawurlencode('floor(5.000000)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('5');

        $response = $this->get('/calc/eval/' . rawurlencode('floor(5.000001)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('5');

        $response = $this->get('/calc/eval/' . rawurlencode('floor(5.999999)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('5');

        $response = $this->get('/calc/eval/' . rawurlencode('floor(-5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-5');

        $response = $this->get('/calc/eval/' . rawurlencode('floor(-5.000000)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-5');

        $response = $this->get('/calc/eval/' . rawurlencode('floor(-5.000001)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-6');

        $response = $this->get('/calc/eval/' . rawurlencode('floor(-5.999999)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('-6');
    }

    public function test_calculator_eval_log() {
        // Base 10
        $response = $this->get('/calc/eval/' . rawurlencode('log(5, 10)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('0.698');

        $response = $this->get('/calc/eval/' . rawurlencode('log(5.54343, 10)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('0.7437');

        $response = $this->get('/calc/eval/' . rawurlencode('log(-5, 10)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('NAN');

        $response = $this->get('/calc/eval/' . rawurlencode('log(-5.54343, 10)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('NAN');

        // Base 2
        $response = $this->get('/calc/eval/' . rawurlencode('log(5, 2)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('2.321');

        $response = $this->get('/calc/eval/' . rawurlencode('log(5.54343, 2)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('2.4707');

        $response = $this->get('/calc/eval/' . rawurlencode('log(-5, 2)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('NAN');

        $response = $this->get('/calc/eval/' . rawurlencode('log(-5.54343, 2)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('NAN');
    }

    public function test_calculator_eval_max() {
        $response = $this->get('/calc/eval/' . rawurlencode('max(5,10)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('10');

        $response = $this->get('/calc/eval/' . rawurlencode('max(10,5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('10');

        $response = $this->get('/calc/eval/' . rawurlencode('max(5.5,10.5)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('10.5');

        $response = $this->get('/calc/eval/' . rawurlencode('max(10.5,5.5)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('10.5');
    }

    public function test_calculator_eval_min() {
        $response = $this->get('/calc/eval/' . rawurlencode('min(5,10)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('5');

        $response = $this->get('/calc/eval/' . rawurlencode('min(10,5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('5');

        $response = $this->get('/calc/eval/' . rawurlencode('min(5.5,10.5)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('5.5');

        $response = $this->get('/calc/eval/' . rawurlencode('min(10.5,5.5)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('5.5');
    }

    public function test_calculator_eval_mod() {
        $response = $this->get('/calc/eval/' . rawurlencode('mod(15,5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('0');

        $response = $this->get('/calc/eval/' . rawurlencode('mod(16,5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('1');

        $response = $this->get('/calc/eval/' . rawurlencode('mod(16.5,5)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('1.5');

        $response = $this->get('/calc/eval/' . rawurlencode('mod(15.5,5)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('0.5');

        $response = $this->get('/calc/eval/' . rawurlencode('mod(16.5,5.5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('0');

        $response = $this->get('/calc/eval/' . rawurlencode('mod(15.5,5.5)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('4.5');

        $response = $this->get('/calc/eval/' . rawurlencode('mod(16,5.5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('5');

        $response = $this->get('/calc/eval/' . rawurlencode('mod(15,5.5)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('4');
    }

    public function test_calculator_eval_pi() {
        $response = $this->get('/calc/eval/' . rawurlencode('pi()'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('3.14');
    }

    public function test_calculator_eval_sin() {
        $response = $this->get('/calc/eval/' . rawurlencode('sin(0)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('0');

        $response = $this->get('/calc/eval/' . rawurlencode('sin(1)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('0.84147');

        $response = $this->get('/calc/eval/' . rawurlencode('sin(5.5)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('-0.70554');
    }

    public function test_calculator_eval_sqrt() {
        $response = $this->get('/calc/eval/' . rawurlencode('sqrt(16)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('4');

        $response = $this->get('/calc/eval/' . rawurlencode('sqrt(0)'));
        $response->assertStatus(200);
        $response->assertSeeTextExact('0');

        $response = $this->get('/calc/eval/' . rawurlencode('sqrt(16.5)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('4.0620');
    }

    public function test_calculator_eval_tan() {
        $response = $this->get('/calc/eval/' . rawurlencode('tan(0)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('0');

        $response = $this->get('/calc/eval/' . rawurlencode('tan(1)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('1.557');

        $response = $this->get('/calc/eval/' . rawurlencode('tan(5.5)'));
        $response->assertStatus(200);
        $response->assertTextStartsWith('-0.9955');
    }
}
