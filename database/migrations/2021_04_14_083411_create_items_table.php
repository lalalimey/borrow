<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity');
            $table->string('unit');
            $table->mediumText('condition');
            $table->mediumText('log_list')->nullable();
            $table->boolean('disposable')->default(1);
            $table->enum('owner', ['SMCU', 'syringe', 'photo'])->default('syringe');
            $table->timestamps();
        });

        DB::table('items')->insert([
            ['name' => 'วอร์', 'quantity' => 5, 'unit' => 'ชุด','condition' => "วอร์ 1 ชุด ประกอบด้วย\n- วอร์สีแดง 5 ตัว\n- เสาอากาศ 5 อัน\n- แท่นชาร์จ 2 ตัว" , 'disposable' => 0, 'owner' => 'SMCU'],
            ['name' => 'ลำโพง สพจ.', 'quantity' => 7, 'unit' => 'ชุด','condition' => "ลำโพง สพจ. 1 ชุด ประกอบด้วย\n- ลำโพงล้อเลื่อนบลูทูธ 1 ตัว\n- ไมค์คู่กัน 2 ตัว\n- สายชาร์จ 1 อัน" , 'disposable' => 0, 'owner' => 'SMCU'],
            ['name' => 'กล่องพยาบาล', 'quantity' => 4, 'unit' => 'กล่อง','condition' => "โปรดบันทึกข้อมูลการใช้กล่องพยาบาลจาก QR code บนกล่องทุกครั้งที่ใช้" , 'disposable' => 0, 'owner' => 'SMCU'],
            ['name' => 'ชุดราชปะแตน', 'quantity' => 40, 'unit' => 'ชุด','condition' => "โปรดซักชุดราชปะแตนก่อนนำมาคืนทุกครั้ง!!\n\nชุดราชปะแตน 1 ชุด ประกอบด้วย\n- เสื้อราชปะแตนสีขาว 1 ตัว\n- กระดุม 5 เม็ด\n- แผงคอสีเขียว 1 อัน\n- พระเกี้ยว 2 องค์" , 'disposable' => 0, 'owner' => 'SMCU'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
