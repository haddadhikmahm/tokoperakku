<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengerajin', function (Blueprint $table) {
            if (!Schema::hasColumn('pengerajin', 'foto_pengerajin')) {
                $table->string('foto_pengerajin')->nullable()->after('alamat_pengerajin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengerajin', function (Blueprint $table) {
            $table->dropColumn('foto_pengerajin');
        });
    }
};
