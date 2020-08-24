<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialOperationsTable extends Migration
{
    const TABLE_NAME = 'financial_operations';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->uuid('transfer_id')->index();
            $table->integer('user_id');
            $table->integer('counterparty_user_id')->index();
            $table->bigInteger('amount');
            $table->timestamps();
            $table->unique(['user_id', 'transfer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
