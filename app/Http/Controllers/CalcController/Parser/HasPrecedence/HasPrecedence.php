<?php

namespace App\Http\Controllers\CalcController\Parser\HasPrecedence;

/**
 * Interface for types that have a precedence and left-associativity.
 *
 * These types are used to determine the order of operations in the Parser.
 */
interface HasPrecedence {
    public function precedence(): int;
    public function left_associative(): bool;
}
