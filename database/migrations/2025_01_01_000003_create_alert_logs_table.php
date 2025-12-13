<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alert_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('channel');
            $table->string('address');
            $table->string('bot')->nullable();
            $table->string('status'); // success / failure
            $table->string('subject')->nullable();
            $table->text('message');
            $table->json('details')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('alert_logs');
    }
};
