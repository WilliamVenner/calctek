<?php

namespace Tests\Feature;

use Tests\ExactTestCase;

class CalcBasicTests extends ExactTestCase
{
    public function test_calculator_view_can_be_rendered()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_calculator_can_add_integers() {
        $response = $this->get('/calc/add/1/2');
        $response->assertStatus(200);
        $response->assertSeeTextExact('3');
    }

    public function test_calculator_can_add_floats() {
        $response = $this->get('/calc/add/1.5/2.5');
        $response->assertStatus(200);
        $response->assertSeeTextExact('4');
    }

    public function test_calculator_can_sub_integers() {
        $response = $this->get('/calc/sub/1/2');
        $response->assertStatus(200);
        $response->assertSeeTextExact('-1');
    }

    public function test_calculator_can_sub_floats() {
        $response = $this->get('/calc/sub/1.5/5.5');
        $response->assertStatus(200);
        $response->assertSeeTextExact('-4');
    }

    public function test_calculator_can_mul_integers() {
        $response = $this->get('/calc/mul/2/3');
        $response->assertStatus(200);
        $response->assertSeeTextExact('6');
    }

    public function test_calculator_can_mul_floats() {
        $response = $this->get('/calc/mul/2.5/3.5');
        $response->assertStatus(200);
        $response->assertTextStartsWith('8.75');
    }

    public function test_calculator_can_div_integers() {
        $response = $this->get('/calc/div/6/3');
        $response->assertStatus(200);
        $response->assertSeeTextExact('2');
    }

    public function test_calculator_can_div_floats() {
        $response = $this->get('/calc/div/7.5/2.5');
        $response->assertStatus(200);
        $response->assertSeeTextExact('3');
    }
}
