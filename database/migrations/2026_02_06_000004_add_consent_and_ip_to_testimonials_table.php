<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->boolean('is_consent_given')->default(false)->after('author_company');
            $table->string('ip_address', 45)->nullable()->after('is_consent_given');

            $table->index('is_consent_given');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropIndex(['is_consent_given']);
            $table->dropIndex(['ip_address']);
            $table->dropColumn(['is_consent_given', 'ip_address']);
        });
    }
};
