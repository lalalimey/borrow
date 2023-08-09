<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KuruLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kuru_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->mediumText('item_list')->nullable();
            $table->mediumText('purpose')->nullable();
            $table->mediumText('place')->nullable();
            $table->enum('status', ['PENDING', 'CANCELLED','BORROWED', 'RETURNED', 'COMPLETE','BROKEN'])->default('PENDING');
            $table->date('borrow_date')->nullable();
            $table->date('due_date')->nullable();
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
        //
    }
}
