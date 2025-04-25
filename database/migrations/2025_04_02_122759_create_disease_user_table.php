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
        if (!Schema::hasTable('disease_user')) {
            Schema::create('disease_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('disease_id')->unsigned();
                $table->bigInteger('user_id')->unsigned();
                $table->integer('repetitions')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disease_user');
    }
};
