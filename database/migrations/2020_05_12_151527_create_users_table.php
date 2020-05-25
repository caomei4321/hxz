<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->comment('姓名');
			$table->unsignedInteger('age')->comment('年龄');
			$table->string('phone')->comment('手机号');
			$table->string('password')->comment('密码');
			$table->string('open_id')->nullable(true);
			$table->string('wx_session_key')->nullable(true);
			$table->unsignedInteger('category_id')->comment('人员分类/派驻机构');
			$table->string('community')->comment('责任社区');
			$table->unsignedInteger('integral')->default(0)->comment('积分');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
