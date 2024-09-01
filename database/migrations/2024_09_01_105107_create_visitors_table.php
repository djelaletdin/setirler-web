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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('device')->nullable();
            $table->string('operating_system')->nullable();
            $table->timestamp('visited_at')->useCurrent();

            $table->index('ip_address');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
