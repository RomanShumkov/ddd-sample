<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundsTransfersTable extends Migration
{
    const TABLE_NAME = 'funds_transfers';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('origin_id')->index();
            $table->integer('destination_id')->index();
            $table->bigInteger('amount');
            $table->enum('state', \App\FundsTransfer::getPossibleStates())->index();
            $table->boolean('is_final_state');
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
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
