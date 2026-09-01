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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('frame_design')->nullable()->after('notes');
        });

        Schema::table('photos', function (Blueprint $table) {
            $table->boolean('is_collage')->default(false)->after('file_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('frame_design');
        });

        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn('is_collage');
        });
    }
};
