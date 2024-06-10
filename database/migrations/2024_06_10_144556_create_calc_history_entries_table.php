<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calc_history_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('input_expression');

            // This is deliberate: we trade off memory for correctness, consistency and robustness here.
            // * Data loss could occur if we ever decide to, for example, switch database drivers.
            // * Data loss could occur if changes are made to how PHP handles floating point numbers and integers.
            // * Data loss could occur between different CPU architectures and instruction sets.
            $table->string('output');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calc_history_entries');
    }
};
