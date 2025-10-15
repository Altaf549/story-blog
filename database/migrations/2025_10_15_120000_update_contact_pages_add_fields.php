<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contact_pages')) {
            Schema::table('contact_pages', function (Blueprint $table) {
                if (!Schema::hasColumn('contact_pages', 'phone_no')) {
                    $table->string('phone_no')->nullable()->after('title');
                }
                if (!Schema::hasColumn('contact_pages', 'email')) {
                    $table->string('email')->nullable()->after('phone_no');
                }
                if (!Schema::hasColumn('contact_pages', 'address')) {
                    $table->text('address')->nullable()->after('email');
                }
                if (Schema::hasColumn('contact_pages', 'content')) {
                    $table->dropColumn('content');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contact_pages')) {
            Schema::table('contact_pages', function (Blueprint $table) {
                if (!Schema::hasColumn('contact_pages', 'content')) {
                    $table->longText('content')->nullable()->after('title');
                }
                if (Schema::hasColumn('contact_pages', 'address')) {
                    $table->dropColumn('address');
                }
                if (Schema::hasColumn('contact_pages', 'email')) {
                    $table->dropColumn('email');
                }
                if (Schema::hasColumn('contact_pages', 'phone_no')) {
                    $table->dropColumn('phone_no');
                }
            });
        }
    }
};


