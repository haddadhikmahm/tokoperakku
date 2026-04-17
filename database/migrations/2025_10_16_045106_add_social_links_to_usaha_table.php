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
        Schema::table('usaha', function (Blueprint $table) {
            $table->string('link_tokopedia_usaha')->nullable();
            $table->string('link_shopee_usaha')->nullable();
            $table->string('link_instagram_usaha')->nullable();
            $table->string('link_tiktok_usaha')->nullable();
            $table->string('link_facebook_usaha')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('usaha', function (Blueprint $table) {
            $table->dropColumn([
                'link_tokopedia_usaha',
                'link_shopee_usaha',
                'link_instagram_usaha',
                'link_tiktok_usaha',
                'link_facebook_usaha',
            ]);
        });
    }
};
