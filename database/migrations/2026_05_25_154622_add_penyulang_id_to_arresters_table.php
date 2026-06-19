<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arresters', function (Blueprint $table) {

            if (!Schema::hasColumn('arresters', 'penyulang_id')) {

                $table->foreignId('penyulang_id')
                    ->nullable()
                    ->after('cabang')
                    ->constrained('penyulangs')
                    ->nullOnDelete();

            }
        });
    }

    public function down(): void
    {
        Schema::table('arresters', function (Blueprint $table) {

            if (Schema::hasColumn('arresters', 'penyulang_id')) {

                $table->dropForeign(['penyulang_id']);
                $table->dropColumn('penyulang_id');

            }
        });
    }
};