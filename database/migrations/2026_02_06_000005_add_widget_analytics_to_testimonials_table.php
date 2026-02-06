<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->unsignedBigInteger('views_count')->default(0)->after('display_order');
            $table->unsignedBigInteger('click_count')->default(0)->after('views_count');

            $table->index('views_count');
            $table->index('click_count');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropIndex(['views_count']);
            $table->dropIndex(['click_count']);
            $table->dropColumn(['views_count', 'click_count']);
        });
    }
};
