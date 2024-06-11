<?php

namespace Tests;

use Illuminate\Testing\TestResponse;
use Illuminate\Testing\Assert as PHPUnit;

class ExactTestResponse extends TestResponse {
    public function assertSeeTextExact($value)
    {
        PHPUnit::assertEquals($value, $this->getContent());
        return $this;
    }

    public function assertTextStartsWith($value)
    {
        PHPUnit::assertStringStartsWith($value, $this->getContent());
        return $this;
    }
}
