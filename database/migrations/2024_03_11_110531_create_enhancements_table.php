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
        Schema::create('enhancements', function (Blueprint $table) {
            $table->id();
            $table->string('enhancement_title')->nullable();
            $table->string('enhancement_description')->nullable();
            $table->string('version_id')->nullable();
            $table->string('project_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enhancements');
    }
};
