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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->references('id')->on('sites')->onDelete('Cascade');
            $table->string('url')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('last_check', $precision = 0)->nullable();
            $table->timestamp('up_at', $precision = 0)->nullable();
            $table->timestamp('down_at', $precision = 0)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->smallInteger('code')->nullable();
            $table->timestamps();

            $table->index(['url','last_check']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
