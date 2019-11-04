<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DbExtender {

    /**
     * default Parameters
     * @param Class $table Object of class name Blueprint
     * @param Bool $userParent Set <b>TRUE</b> to set users id as a foreign key
     * @author Gaurav Sethi <ergauravsethi376@gmail.com>
     * @return default Parameters
     */
    public static function defaultParams($table, $userParent = false) {
        //default variables
        $table->text('params')->nullable();
        $table->enum('state', [0, 1])->default(0)->comment('0->Unactive, 1->Active');
//        $table->timestamps();
        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        $table->softDeletes();
        if ($userParent):
            $table->integer('created_by')->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('updated_by')->unsigned()->index();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        endif;
    }

}
