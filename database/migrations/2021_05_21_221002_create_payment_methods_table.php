<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->foreignId('account_payment_provider_profile_id')
                ->constrained('account_payment_provider_profiles')
                ->onUpdate('cascade');
            $table->string('external_id')->index()->nullable();
            $table->string('card_type');
            $table->unsignedSmallInteger('last4');
            $table->timestamps();
            $table->softDeletes();
        });

        // Adding Foreign Key [Default Payment Method]
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign(['default_payment_method_id'])
                ->references('id')
                ->on('payment_methods')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
