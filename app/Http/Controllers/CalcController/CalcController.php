<?php

namespace App\Http\Controllers\CalcController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CalcController\Lexer\Lexer;
use App\Http\Controllers\CalcController\Parser\Parser;
use App\Models\CalcHistoryEntry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalcController extends Controller
{
    /**
     * Display the index page of the CalcController.
     */
    public function index(Request $request) {
        return Inertia::render('Calculator', [
            'calc_history' => $request->user()?->calc_entries ?? [],
        ]);
    }

    /**
     * Compute the given mathematical expression.
     *
     * @param string $expr The expression to evaluate.
     */
    public function eval(Request $request, string $expr)
    {
        try {
            // Lex the expression...
            $tokens = (new Lexer)->lex($expr);

            // Parse the tokens...
            $evaluator = (new Parser($tokens))->infix_to_rpn($tokens);

            // Evaluate the expression...
            $result = $evaluator->evaluate();
            $result = strval($result);

            // If we're logged in, save this to the user's history
            if ($user = $request->user()) {
                $entry = new CalcHistoryEntry();
                $entry->input_expression = $expr;
                $entry->output = $result;
                $entry->user_id = $user->id;
                $entry->save();
            }

            return response($result, 200);
        } catch (CalcException $e) {
            return response($e->getMessage(), $e->httpCode);
        }
    }

    /**
     * Adds two numbers.
     *
     * @param int $a The first number.
     * @param int $b The second number.
     * @return int The sum of $a and $b.
     */
    public function add($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b))
            return response('', 400);

        return $a + $b;
    }

    /**
     * Subtracts two numbers.
     *
     * @param int $a The first number.
     * @param int $b The second number.
     * @return int The difference of $a and $b.
     */
    public function sub($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b))
            return response('', 400);

        return $a - $b;
    }

    /**
     * Multiplies two numbers.
     *
     * @param int $a The first number.
     * @param int $b The second number.
     * @return int The product of $a and $b.
     */
    public function mul($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b))
            return response('', 400);

        return $a * $b;
    }

    /**
     * Divides two numbers.
     *
     * @param int $a The first number.
     * @param int $b The second number.
     * @return int The quotient of $a and $b.
     */
    public function div($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b))
            return response('', 400);

        return $a / $b;
    }
}
