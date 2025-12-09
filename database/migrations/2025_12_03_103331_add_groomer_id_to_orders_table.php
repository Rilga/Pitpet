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
        Schema::table('orders', function (Blueprint $table) {
            // Menambahkan kolom groomer_id setelah kolom status
            $table->foreignId('groomer_id')
                  ->nullable()              // Boleh kosong (karena awal booking belum ada groomer)
                  ->after('status')         // Posisi kolom (opsional, agar rapi)
                  ->constrained('users')    // Terhubung ke tabel users
                  ->onDelete('set null');   // Jika user dihapus, kolom ini jadi NULL (data order aman)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus foreign key constraint dulu
            $table->dropForeign(['groomer_id']);
            
            // Baru hapus kolomnya
            $table->dropColumn('groomer_id');
        });
    }
};
