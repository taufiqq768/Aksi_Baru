<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Extend user_password column to support bcrypt hashing (255 chars)
     */
    public function up(): void
    {
        Schema::table('tb_users', function (Blueprint $table) {
            // Change user_password from VARCHAR(50) to VARCHAR(255)
            $table->string('user_password', 255)->change();

            // Add timestamps if not exist
            if (!Schema::hasColumn('tb_users', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('tb_users', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_users', function (Blueprint $table) {
            // Revert to VARCHAR(50)
            $table->string('user_password', 50)->change();
        });
    }
};
