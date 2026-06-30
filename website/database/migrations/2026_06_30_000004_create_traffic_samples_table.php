<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traffic_samples', function (Blueprint $table) {
            $table->id();
            $table->timestamp('sample_at')->nullable();
            $table->unsignedInteger('normal_flows')->default(0);
            $table->unsignedInteger('suspicious_flows')->default(0);
            $table->unsignedInteger('threshold')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traffic_samples');
    }
};
