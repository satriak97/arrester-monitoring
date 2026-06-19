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
        Schema::table('gis', function (Blueprint $table) {
            if (Schema::hasColumn('gis', 'lokasi')) {
                $table->dropColumn('lokasi');
            }

            if (Schema::hasColumn('gis', 'cabang')) {
                $table->dropColumn('cabang');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gis', function (Blueprint $table) {
            $table->string('lokasi')->nullable();
            $table->string('cabang')->nullable();
        });
    }
};
