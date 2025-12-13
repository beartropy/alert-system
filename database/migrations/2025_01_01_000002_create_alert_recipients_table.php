<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alert_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('alert_channel_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->string('address');
            $table->string('bot')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('alert_recipients');
    }
};
