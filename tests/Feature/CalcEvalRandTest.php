<?php

namespace Tests\Feature;

use Tests\ExactTestCase;

class CalcEvalRandTest extends ExactTestCase
{
    private const TIMEOUT = 5;

    public function test_calculator_eval_rand_function_with_integers() {
        $start = time();

        $found_one = false;
        $found_two = false;
        $found_three = false;
        while (!($found_one && $found_two && $found_three) && time() - $start < static::TIMEOUT) {
            $response = $this->get('/calc/eval/' . rawurlencode('rand(1,3)'));
            $response->assertStatus(200);
            $n = (int)$response->baseResponse->content();
            if ($n === 1) {
                $found_one = true;
            } else if ($n === 2) {
                $found_two = true;
            } else if ($n === 3) {
                $found_three = true;
            } else {
                $this->fail("Unexpected result: $n");
            }
        }

        $this->assertTrue($found_one);
        $this->assertTrue($found_two);
        $this->assertTrue($found_three);
    }

    public function test_calculator_eval_rand_function_with_floats() {
        $start = time();

        $found_decimal = false;
        $mean = 0;
        $i = 0;
        while ((!$found_decimal || $i < 100) && time() - $start < static::TIMEOUT) {
            $response = $this->get('/calc/eval/' . rawurlencode('rand(1.0,3.0)'));
            $response->assertStatus(200);
            $n = (float)$response->baseResponse->content();
            if (fmod($n, 1) !== 0) {
                $found_decimal = true;
            }

            if ($n > 3 || $n < 1) {
                $this->fail("Unexpected result: $n");
            } else {
                $mean += $n;
                $i++;
            }
        }

        $mean /= $i;

        $this->assertTrue($found_decimal);
        $this->assertGreaterThan(1.2, $mean);
        $this->assertLessThan(2.8, $mean);
    }
}
