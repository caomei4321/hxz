<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHandoverRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('handover_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sender_user')->comment('交班人');
            $table->unsignedInteger('recipient_user')->comment('接班人');
            $table->string('content')->nullable(true);
            $table->unsignedInteger('status')->default(0)->comment('是否已读，0未读，1已读');
            $table->timestamp('read_time')->nullable(true);
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
        Schema::dropIfExists('handover_records');
    }
}
