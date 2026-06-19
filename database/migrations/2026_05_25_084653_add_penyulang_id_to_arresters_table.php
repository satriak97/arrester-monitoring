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
        Schema::table('arresters', function (Blueprint $table) {
            $table->foreignId('penyulang_id')
                ->nullable()
                ->after('cabang')
                ->constrained('penyulangs')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arresters', function (Blueprint $table) {
            //
        });
    }
};
