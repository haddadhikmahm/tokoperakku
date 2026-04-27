<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('usaha', function (Blueprint $table) {
            if (!Schema::hasColumn('usaha', 'link_website_usaha')) {
                $table->string('link_website_usaha')->nullable();
            }
            if (!Schema::hasColumn('usaha', 'link_wa_usaha')) {
                $table->string('link_wa_usaha')->nullable();
            }
            if (!Schema::hasColumn('usaha', 'spesialisasi_usaha')) {
                $table->text('spesialisasi_usaha')->nullable();
            }
            if (!Schema::hasColumn('usaha', 'foto_tempat')) {
                $table->text('foto_tempat')->nullable(); // JSON or comma separated paths
            }
        });
    }

    public function down(): void
    {
        Schema::table('usaha', function (Blueprint $table) {
            $table->dropColumn([
                'link_website_usaha',
                'link_wa_usaha',
                'spesialisasi_usaha',
                'foto_tempat',
            ]);
        });
    }
};
