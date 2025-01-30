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
        Schema::table('absen', function (Blueprint $table) {

            if (!Schema::hasColumn('absen', 'id_siswa')) {
                $table->unsignedBigInteger('id_siswa');
            }
            if (!Schema::hasColumn('absen', 'id_kelas')) {
                $table->unsignedBigInteger('id_kelas');
            }

            // Tambahkan foreign key
            $table->foreign('id_siswa')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('id_kelas')->references('id')->on('kelas')->onDelete('cascade');
        });

        Schema::table('kelas_siswa', function (Blueprint $table) {

            if (!Schema::hasColumn('kelas_siswa', 'id_siswa')) {
                $table->unsignedBigInteger('id_siswa');
            }
            if (!Schema::hasColumn('kelas_siswa', 'id_kelas')) {
                $table->unsignedBigInteger('id_kelas');
            }

            $table->foreign('id_siswa')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('id_kelas')->references('id')->on('kelas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absen', function (Blueprint $table) {

            $table->dropForeign(['id_siswa']);
            $table->dropForeign(['id_kelas']);
            $table->dropColumn(['id_siswa', 'id_kelas']);
        });

        Schema::table('kelas_siswa', function (Blueprint $table) {

            $table->dropForeign(['id_siswa']);
            $table->dropForeign(['id_kelas']);
            $table->dropColumn(['id_siswa', 'id_kelas']);
        });
    }
};
