<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompitionLeadBoardsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('compition_lead_boards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competition_id')->unsigned()->index();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('score')->nullable();
            $table->integer('count');
            $table->enum('winner', [0,1,2])->default(2)->comment('0->Winner, 1->Loser,2->not-yet-declared');
            App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('compition_lead_boards');
    }

}
