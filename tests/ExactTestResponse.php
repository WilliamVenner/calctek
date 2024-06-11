<?php

namespace Tests;

use Illuminate\Testing\TestResponse;
use Illuminate\Testing\Assert as PHPUnit;

class ExactTestResponse extends TestResponse {
    public function assertSeeTextExact($value)
    {
        PHPUnit::assertEquals($value, $this->getContent(), 'Expected "' . $value . '" but got "' . $this->getContent() . '"');
        return $this;
    }

    public function assertTextStartsWith($value)
    {
        PHPUnit::assertStringStartsWith($value, $this->getContent());
        return $this;
    }

    public function assertStatus($status)
    {
        $message = $this->statusMessageWithDetails($status, $actual = $this->getStatusCode());

        $extra = '';
        if ($this->getStatusCode() === 400) {
            $extra = "\n(\"" . $this->getContent() . '")';
        }

        PHPUnit::assertSame($actual, $status, $message . $extra);

        return $this;
    }
}
