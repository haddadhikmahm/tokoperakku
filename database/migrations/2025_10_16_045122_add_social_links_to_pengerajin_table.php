<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengerajin', function (Blueprint $table) {
            $table->string('link_tokopedia_pengerajin')->nullable();
            $table->string('link_shopee_pengerajin')->nullable();
            $table->string('link_instagram_pengerajin')->nullable();
            $table->string('link_tiktok_pengerajin')->nullable();
            $table->string('link_facebook_pengerajin')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pengerajin', function (Blueprint $table) {
            $table->dropColumn([
                'link_tokopedia_pengerajin',
                'link_shopee_pengerajin',
                'link_instagram_pengerajin',
                'link_tiktok_pengerajin',
                'link_facebook_pengerajin',
            ]);
        });
    }
};
