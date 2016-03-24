<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text');
            $table->integer('project_id')->unsigned();
            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')
                ->on('project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_notes', function (Blueprint $table) {
            $table->dropForeign('project_notes_project_id_foreign');
        });
        Schema::drop('project_notes');
    }
}
