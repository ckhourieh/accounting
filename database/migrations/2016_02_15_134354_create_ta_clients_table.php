<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ta_clients', function(Blueprint $table)
		{
			$table->increments('client_id');
			$table->string('name', 45);
			$table->text('desc');
			$table->string('email');
			$table->string('phone');
			$table->text('address');
			$table->string('owner');
			$table->string('contact_name');
			$table->integer('accounting_id');
			$table->boolean('hidden');
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
		Schema::drop('ta_clients');
	}

}
