<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama')->nullable()->after('username');
            $table->string('no_hp')->nullable()->after('email');
            $table->enum('gender', ['Pria', 'Wanita'])->nullable()->after('no_hp');
            $table->integer('usia')->nullable()->after('gender');
            $table->text('alamat')->nullable()->after('usia');
            $table->string('foto')->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama', 'no_hp', 'gender', 'usia', 'alamat', 'foto']);
        });
    }
};
