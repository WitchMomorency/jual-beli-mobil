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
        Schema::table('transactions', function (Blueprint $table) {
            // Pastikan kolom product_id ada sebelum menghapus foreign key
            $table->dropForeign(['product_id']);

            // Cek apakah kolom total_price ada
            if (Schema::hasColumn('transactions', 'total_price')) {
                // Rename kolom total_price ke quantity
                $table->renameColumn('total_price', 'quantity');

                // Ubah tipe data menjadi unsignedInteger
                $table->unsignedInteger('quantity')->change();
            } else {
                // Jika tidak ada, tambahkan kolom quantity jika belum ada
                if (!Schema::hasColumn('transactions', 'quantity')) {
                    $table->unsignedInteger('quantity')->after('product_id');
                }
            }

            // Tambahkan kembali foreign key
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Hapus foreign key dulu
            $table->dropForeign(['product_id']);

            // Cek apakah kolom quantity ada
            if (Schema::hasColumn('transactions', 'quantity')) {
                $table->dropColumn('quantity');
            }

            // Tambahkan kembali kolom total_price jika belum ada
            if (!Schema::hasColumn('transactions', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable()->after('product_id');
            }

            // Kembalikan foreign key
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};