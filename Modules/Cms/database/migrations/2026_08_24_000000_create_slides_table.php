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
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
            $table->string('slug')->unique();
            $table->json('link_text')->nullable();
            $table->string('image');
            $table->string('link')->nullable();
            $table->integer('rank')->default(0)->index();
            $table->enum('status', ['Published', 'Archived'])->default('Published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
