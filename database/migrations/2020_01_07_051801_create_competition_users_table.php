<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('competition_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('score')->default(0);
            $table->enum('status', ['not_yet', 'winner', 'looser'])->default('not_yet');
            $table->bigInteger('player_id')->unsigned()->index();
            $table->foreign('player_id')->references('id')->on('users')->onDelete('cascade');
            $table->Integer('competition_id')->unsigned()->index();
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('competition_users');
    }

}
