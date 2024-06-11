<?php

namespace App\Http\Controllers\CalcController;

trait NamedCalcClass {
    public function name(): string {
        return (new \ReflectionClass($this))->getShortName();
    }
}
