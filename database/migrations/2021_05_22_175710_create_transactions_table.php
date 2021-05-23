<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->foreignId('account_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('payment_method_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('transactions')
                ->cascadeOnUpdate();
            $table->char('type', 1);
            $table->string('currency', 3)->default('GBP');
            $table->integer('amount');
            $table->text('remark')->nullable();
            $table->char('status', 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
