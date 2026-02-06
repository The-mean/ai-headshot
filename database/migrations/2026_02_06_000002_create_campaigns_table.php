<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'active', 'paused', 'archived'])->default('draft');
            $table->json('settings')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'slug']);
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
