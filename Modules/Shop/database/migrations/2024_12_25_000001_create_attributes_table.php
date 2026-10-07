<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->json('admin_name');
            $table->string('type')->default('text'); // text, select, boolean, etc.
            $table->boolean('is_required')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->json('options')->nullable(); // For select type: array of options
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};