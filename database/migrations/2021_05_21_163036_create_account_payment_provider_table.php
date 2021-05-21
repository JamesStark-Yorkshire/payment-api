<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountPaymentProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_payment_provider', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_provider_id')->constrained()->onUpdate('cascade');
            $table->foreignId('payment_account_id')->constrained()->onUpdate('cascade');
            $table->string('external_account_id');
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
        Schema::dropIfExists('account_payment_provider');
    }
}
