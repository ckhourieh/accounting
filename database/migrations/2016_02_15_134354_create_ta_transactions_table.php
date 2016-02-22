<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ta_transactions', function(Blueprint $table)
		{
			$table->increments('transaction_id');
			$table->integer('invoice_id');
			$table->string('source');
			$table->string('amount');
			$table->string('contact');
			$table->date('date');
			$table->boolean('type');
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
		Schema::drop('ta_transactions');
	}

}
