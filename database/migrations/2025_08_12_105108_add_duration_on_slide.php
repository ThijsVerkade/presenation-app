<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            if (!Schema::hasColumn('slides', 'duration_seconds')) {
                $table->unsignedInteger('duration_seconds')->default(5);
            }
        });
    }

    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            if (Schema::hasColumn('slides', 'duration_seconds')) {
                $table->dropColumn('duration_seconds');
            }
        });
    }
};
