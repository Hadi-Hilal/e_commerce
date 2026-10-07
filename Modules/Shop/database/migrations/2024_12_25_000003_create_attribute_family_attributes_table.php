<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_family_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_family_id')->constrained('attribute_families')->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained('attributes')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['attribute_family_id', 'attribute_id'], 'attr_family_attr_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_family_attributes');
    }
};