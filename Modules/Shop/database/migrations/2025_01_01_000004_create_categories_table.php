<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->foreignId('attribute_family_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('slug')->unique();
            $table->json('seo_data')->nullable();
            $table->timestamps();
            // Index for efficient tree queries
            $table->index(['parent_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
