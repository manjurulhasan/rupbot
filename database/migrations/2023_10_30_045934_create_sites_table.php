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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('project', 50)->nullable();
            $table->string('url', 50)->nullable();
            $table->string('manager', 50)->nullable();
            $table->timestamp('last_check', $precision = 0)->nullable();
            $table->timestamp('up_at', $precision = 0)->nullable();
            $table->timestamp('down_at', $precision = 0)->nullable();
            $table->tinyInteger('status')->default(2);
            $table->time('duration')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->smallInteger('code')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index(['url','last_check']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
