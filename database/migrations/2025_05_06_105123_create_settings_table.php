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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('logo')->nullable();
            $table->string('fav-icon')->nullable();
            $table->string('debug_mode')->nullable();
            $table->string('time_zone')->nullable();
            $table->string('app_url')->nullable();
            $table->string('email_username')->nullable();
            $table->string('app_password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
