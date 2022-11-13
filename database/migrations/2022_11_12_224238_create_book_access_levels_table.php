<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_access_levels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('book_id')->constrained();
            $table->foreignId('access_level_id')->constrained();

            $table->unique(['book_id', 'access_level_id']);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_access_levels');
    }
};
