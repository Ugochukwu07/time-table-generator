<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('capacity');
            $table->boolean('is_available')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed with the halls the scheduler previously had hardcoded, so
        // switching it over to read from this table doesn't change behavior.
        DB::table('venues')->insert([
            ['name' => 'Hall 1', 'capacity' => 120, 'is_available' => true, 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hall 2', 'capacity' => 170, 'is_available' => true, 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hall 3', 'capacity' => 75, 'is_available' => true, 'description' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hall 4', 'capacity' => 35, 'is_available' => true, 'description' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
