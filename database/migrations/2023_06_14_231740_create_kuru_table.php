<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKuruTable extends Migration
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
            $table->string('name');
            $table->string('unit');
            $table->string('budget');
            $table->string('storage');
            $table->integer('year');
            $table->mediumText('condition');
            $table->mediumText('log_list')->nullable();
            $table->boolean('disposable')->default(1);
            $table->enum('owner', ['SMCU', 'syringe', 'photo'])->default('syringe');
            $table->enum('status', ['normal','pending fix','borrowed'])->default('normal');
            $table->date('last_check');
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
        Schema::dropIfExists('kuru');
    }
}
