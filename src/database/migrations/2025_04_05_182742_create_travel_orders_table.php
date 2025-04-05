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
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('Foreign key referencing clients');
            $table->unsignedBigInteger('destination_id')->comment('Foreign key referencing destinations');
            $table->date('departure_date')->comment('Date of departure');
            $table->date('return_date')->comment('Date of return');
            $table->unsignedBigInteger('status_id')->comment('Foreign key referencing travel_order_statuses; default is 1 (requested)');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict')
                  ->comment('FK to clients table');

            $table->foreign('destination_id')
                  ->references('id')
                  ->on('destinations')
                  ->onDelete('restrict')
                  ->comment('FK to destinations table');

            $table->foreign('status_id')
                  ->references('id')
                  ->on('travel_order_statuses')
                  ->onDelete('restrict')
                  ->comment('FK to travel_order_statuses table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_orders');
    }
};
