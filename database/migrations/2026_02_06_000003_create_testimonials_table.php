<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->uuid('public_token')->unique();

            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->string('author_company')->nullable();

            $table->enum('source', ['widget_record', 'widget_upload', 'admin_upload'])->default('widget_record');
            $table->enum('status', ['uploaded', 'pending_review', 'approved', 'rejected'])->default('uploaded');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('display_order')->default(0);

            $table->string('storage_disk')->default('r2');
            $table->string('storage_path');
            $table->unsignedBigInteger('file_size_bytes')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->json('meta')->nullable();

            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['campaign_id', 'status', 'created_at']);
            $table->index(['campaign_id', 'status', 'submitted_at']);
            $table->index(['campaign_id', 'published_at', 'display_order']);
            $table->index(['campaign_id', 'approved_at']);
            $table->index(['status', 'created_at']);
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
