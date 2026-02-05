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
        Schema::table('tb_pkpt', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['jenis_audit', 'jumlah']);

            // Add new columns
            $table->integer('rutin')->default(0)->after('bulan');
            $table->integer('khusus')->default(0)->after('rutin');
            $table->integer('tematik')->default(0)->after('khusus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pkpt', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['rutin', 'khusus', 'tematik']);

            // Restore old columns
            $table->string('jenis_audit')->after('bulan');
            $table->integer('jumlah')->after('jenis_audit');
        });
    }
};
