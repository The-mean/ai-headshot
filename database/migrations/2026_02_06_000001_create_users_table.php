<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->enum('plan_status', ['free', 'active', 'past_due', 'canceled'])->default('free');
            $table->string('plan_name')->default('free');
            $table->string('dodo_customer_id')->nullable()->unique();
            $table->timestamp('subscription_ends_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('created_at');
            $table->index('deleted_at');
            $table->index('plan_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
