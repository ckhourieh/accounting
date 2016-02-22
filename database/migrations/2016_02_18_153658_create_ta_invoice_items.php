<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaInvoiceItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ta_invoice_items', function(Blueprint $table)
        {
            $table->increments('invoice_item_id');
            $table->integer('invoice_id');
            $table->string('title');
            $table->string('item_amount');
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
        Schema::drop('ta_invoice_items');
    }
}
