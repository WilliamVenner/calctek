<?php

namespace Tests;

use Illuminate\Testing\TestResponse;
use Illuminate\Testing\Assert as PHPUnit;

/**
 * An extension of the TestResponse class with more appropriate assertion methods.
 */
class ExactTestResponse extends TestResponse {
    /**
     * Asserts that the response exactly matches the given text.
     *
     * @param string $value The expected response text.
     */
    public function assertSeeTextExact($value)
    {
        PHPUnit::assertEquals($value, $this->getContent(), 'Expected "' . $value . '" but got "' . $this->getContent() . '"');
        return $this;
    }

    /**
     * Asserts that the response starts with the given text.
     *
     * @param string $value The text the response text is expected to start with.
     */
    public function assertTextStartsWith($value)
    {
        PHPUnit::assertStringStartsWith($value, $this->getContent());
        return $this;
    }

    /**
     * Asserts that the response HTTP status code matches the given status code.
     *
     * @param int $status The expected HTTP status code.
     */
    public function assertStatus($status)
    {
        $message = $this->statusMessageWithDetails($status, $actual = $this->getStatusCode());

        // We extend the message to show the reason for the HTTP Bad Request response
        $extra = '';
        if ($this->getStatusCode() === 400) {
            $extra = "\n(\"" . $this->getContent() . '")';
        }

        PHPUnit::assertSame($actual, $status, $message . $extra);

        return $this;
    }
}
