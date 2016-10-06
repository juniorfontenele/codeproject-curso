<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProjectFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_files', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('project_id')->unsigned();
	        $table->string('name');
	        $table->text('description');
	        $table->integer('size');
	        $table->string('save_name');
	        $table->string('extension');
	        $table->string('mime_type');
            $table->timestamps();

	        $table->foreign('project_id')
		        ->references('id')
		        ->on('projects')
		        ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('project_files', function(Blueprint $table) {
		    $table->dropForeign('project_files_project_id_foreign');
	    });
        Schema::drop('project_files');
    }
}
