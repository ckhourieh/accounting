<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ta_invoices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('invoice_id')->unique();
			$table->string('title');
			$table->integer('client_id');
			$table->string('amount');
			$table->date('due_date');
			$table->integer('frequency_id');
			$table->boolean('status');
			$table->string('paid');
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
		Schema::drop('ta_invoices');
	}

}
