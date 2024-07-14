<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('strategies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->timestamps();
        });

        (new \Database\Seeders\StrategySeeder())->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategies');
    }
};
