<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attack_events', function (Blueprint $table) {
            $table->id();
            $table->string('attack_type');
            $table->string('source_ip');
            $table->string('target_ip');
            $table->string('protocol', 20);
            $table->string('severity', 20);
            $table->string('status', 30)->default('open');
            $table->unsignedBigInteger('packets')->default(0);
            $table->unsignedInteger('anomaly_score')->default(0);
            $table->unsignedInteger('sla_minutes')->default(0);
            $table->timestamp('occurred_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attack_events');
    }
};
