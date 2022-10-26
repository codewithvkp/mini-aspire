<?php

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
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->id();

            $table->date('payment_date');
            $table->integer('week');
            $table->timestamp('paid_at')->nullable();
            $table->double('amount', 8, 2)->default(0);
            $table->double('interest_amount', 8, 2)->default(0);
            $table->double('interest', 8, 2)->default(0)
                ->comment("Interest percentage");
            $table->double('total_amount', 8, 2)->default(0)
                ->comment("Total amount to be repaid");
            $table->unsignedBigInteger('loan_application_id');

            $table->foreign('loan_application_id')
                ->references('id')
                ->on('loan_applications')
                ->cascadeOnDelete();

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
        Schema::dropIfExists('loan_repayments');
    }
};
