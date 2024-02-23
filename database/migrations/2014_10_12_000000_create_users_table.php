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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('role_id')->nullable();
            $table->string('category_id')->nullable();
            $table->string('manage_title')->nullable();
            $table->string('manage_subtitle')->nullable();
            $table->string('manage_content')->nullable();
            $table->string('manage_support_category')->nullable();
            $table->string('manage_support_subcategory')->nullable();
            $table->string('manage_status')->nullable();
            $table->string('manage_all_ticket')->nullable();
            $table->string('manage_ticket_in_category')->nullable();
            $table->string('manage_own_ticket')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
