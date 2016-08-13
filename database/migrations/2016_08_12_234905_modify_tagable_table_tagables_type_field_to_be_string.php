<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTagableTableTagablesTypeFieldToBeString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagables', function (Blueprint $table) {
            $table->string('tagable_type')->change();
        });
    }

    /**x
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tagables', function (Blueprint $table) {
            //
        });
    }
}
