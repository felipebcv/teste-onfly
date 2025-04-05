<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('travel_order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Status name [requested, approved, cancelled]');
            $table->string('description')->nullable()->comment('Description of the status');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_order_statuses');
    }
};
