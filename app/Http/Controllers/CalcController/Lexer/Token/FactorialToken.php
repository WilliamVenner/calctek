<?php

namespace App\Http\Controllers\CalcController\Lexer\Token;

function factorial($n) {
    if ($n < 0) {
        return NAN; // Factorial is not defined for negative numbers
    } elseif ($n == 0) {
        return 1; // 0! = 1
    } elseif (is_int($n)) {
        $result = 1;
        for ($i = 1; $i <= $n; $i++) {
            $result *= $i;
        }
        return $result;
    } else {
        return gamma($n + 1); // Use the gamma function for non-integer values
    }
}

function gamma($z) {
    static $p = array(
        676.5203681218851,
        -1259.1392167224028,
        771.32342877765313,
        -176.61502916214059,
        12.507343278686905,
        -0.13857109526572012,
        9.9843695780195716e-6,
        1.5056327351493116e-7
    );

    if ($z < 0.5) {
        return M_PI / (sin(M_PI * $z) * gamma(1 - $z));
    } else {
        $z -= 1;
        $x = 0.99999999999980993;
        for ($i = 0; $i < count($p); $i++) {
            $x += $p[$i] / ($z + $i + 1);
        }
        $t = $z + count($p) - 0.5;
        return sqrt(2 * M_PI) * pow($t, $z + 0.5) * exp(-$t) * $x;
    }
}

class FactorialToken extends SymbolToken implements PostfixUnaryOpToken {
    use PostfixUnaryOperator;

    public const SYMBOL = '!';

    public function evaluate(NumberToken $operand) {
        return factorial($operand->value);
    }
}
