<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_uptimes', function (Blueprint $table) {
            $table->id();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('last_ping_at')->nullable();
            $table->string('status')->default('running'); // running, stopped, maintenance
            $table->integer('total_downtime_seconds')->default(0);
            $table->json('downtime_events')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_uptimes');
    }
};