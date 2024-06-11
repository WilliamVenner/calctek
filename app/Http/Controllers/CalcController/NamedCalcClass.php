<?php

namespace App\Http\Controllers\CalcController;

/**
 * This trait defines the `name` method, which uses reflection to get
 *
 * the short name of the class. Used in various CalcController-related classes.
 */
trait NamedCalcClass {
    /**
     * Get the short name of the class.
     * @return string The name of the class.
     */
    public function name(): string {
        return (new \ReflectionClass($this))->getShortName();
    }
}
