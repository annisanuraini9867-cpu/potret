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
            $table->string('studio_name')->nullable()->after('phone');
            $table->string('studio_address')->nullable()->after('studio_name');
            $table->string('studio_city')->nullable()->after('studio_address');
            $table->string('booth_type')->nullable()->after('studio_city');
            $table->string('admin_pin', 6)->default('123456')->after('booth_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['studio_name', 'studio_address', 'studio_city', 'booth_type', 'admin_pin']);
        });
    }
};
