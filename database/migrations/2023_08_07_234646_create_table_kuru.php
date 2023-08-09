<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableKuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kuru', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->string('name');
            $table->string('division');
            $table->string('storage');
            $table->string('budget');
            $table->integer('year');
            $table->enum('status', ['normal', 'pending', 'borrowed', 'broken']);
            $table->date('checkup');
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
        Schema::dropIfExists('table_kuru');
    }
}
