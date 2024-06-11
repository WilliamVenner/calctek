<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\ExactTestCase;

class CalcHistoryTest extends ExactTestCase
{
    public function test_calculator_stores_and_retrieves_eval_history()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/calc/eval/' . rawurlencode('1+2+3+4+5+6'));

        $response->assertStatus(200);
        $response->assertSeeTextExact('21');

        $response = $this
            ->actingAs($user)
            ->get('/calc/eval/' . rawurlencode('1-2-3-4-5-6'));

        $response->assertStatus(200);
        $response->assertSeeTextExact('-19');

        $calc_entries = $user->calc_entries;

        $this->assertCount(2, $calc_entries);

        $this->assertEquals('1+2+3+4+5+6', $calc_entries[0]->input_expression);
        $this->assertEquals('21', $calc_entries[0]->output);

        $this->assertEquals('1-2-3-4-5-6', $calc_entries[1]->input_expression);
        $this->assertEquals('-19', $calc_entries[1]->output);
    }
}
