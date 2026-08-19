<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table): void {
            $table->id();
            $table->string('context_type', 32);
            $table->string('context_slug', 120)->index();
            $table->string('municipality_slug', 120)->nullable()->index();
            $table->string('first_name', 80);
            $table->string('last_name', 120);
            $table->string('email', 190)->index();
            $table->string('phone', 32);
            $table->string('street', 160);
            $table->string('house_number', 24);
            $table->string('postal_code', 12);
            $table->string('city', 120);
            $table->boolean('consent');
            $table->boolean('marketing_consent')->default(false);
            $table->timestamp('consented_at');
            $table->string('status', 32)->default('new')->index();
            $table->text('source_url')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
