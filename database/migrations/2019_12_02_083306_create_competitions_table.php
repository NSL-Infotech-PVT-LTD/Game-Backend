<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompetitionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->date('start_time')->nullable();
            $table->float('fee')->nullable();
            $table->text('prize_image')->nullable();
            $table->text('prize_details')->nullable();
            $table->integer('game_id')->unsigned()->index();
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->bigInteger('competition_category_id')->unsigned()->index();
            $table->foreign('competition_category_id')->references('id')->on('competition_categories')->onDelete('cascade');
            $table->enum('hot_competitions', [0, 1])->default(0)->comment('0->No, 1->Yes');
            App\Helpers\DbExtender::defaultParams($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('competitions');
    }

}
