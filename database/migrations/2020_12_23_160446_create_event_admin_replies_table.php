<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventAdminRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_admin_replies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id')->nullable(true);
            $table->unsignedInteger('event_reply_id')->nullable(true);
            $table->string('reply');
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
        Schema::dropIfExists('event_administrator_reply');
    }
}
