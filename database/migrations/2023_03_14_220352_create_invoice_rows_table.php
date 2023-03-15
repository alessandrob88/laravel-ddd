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
        Schema::create('invoice_rows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('invoice_id');
            $table->string('event')->nullable();
            $table->string('description');
            $table->float('total');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('invoice_id')
                ->references('id')->on('invoices')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_rows');
    }
};
