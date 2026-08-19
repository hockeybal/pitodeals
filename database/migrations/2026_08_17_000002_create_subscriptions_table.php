<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->string('email', 190);
            $table->string('municipality_slug', 120);
            $table->string('municipality_name', 120);
            $table->boolean('deals')->default(true);
            $table->boolean('vacancies')->default(false);
            $table->string('street', 160);
            $table->string('house_number', 24);
            $table->string('postal_code', 12);
            $table->string('city', 120);
            $table->boolean('consent');
            $table->timestamp('consented_at');
            $table->string('status', 32)->default('active')->index();
            $table->timestamps();
            $table->unique(['email', 'municipality_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
