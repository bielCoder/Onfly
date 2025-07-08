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
            // Verifica se a coluna 'register' não existe antes de adicionar
            if (!Schema::hasColumn('users', 'access')) {
                $table->integer('access')-> after('password')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function column(): void
    {
        Schema::column('users', function (Blueprint $table) {
             if (Schema::hasColumn('users', 'access')) {
                $table->dropColumn('access');
            }
        });
    }
};
