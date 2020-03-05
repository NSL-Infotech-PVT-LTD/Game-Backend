<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionUserPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_user_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
          
             $table->bigInteger('competition_user_id')->unsigned()->index();
            $table->foreign('competition_user_id')->references('id')->on('competition_users')->onDelete('cascade');
            $table->text('payment')->nullable();
            App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_user_payments');
    }
}
