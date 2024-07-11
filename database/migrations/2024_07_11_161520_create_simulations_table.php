<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modality_id');
            $table->foreign('modality_id')->references('id')->on('modalities');
            $table->double('total_amount');
            $table->double('amount_requested');
            $table->double('monthly_interest_rate');
            $table->integer('quantity_installment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulations');
    }
};
