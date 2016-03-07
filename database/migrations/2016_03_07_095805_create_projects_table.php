<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->string('name');
            $table->string('description')->default('');
            $table->integer('progress');
            $table->string('status');
            $table->timestamp('due_date');
            $table->timestamps();


            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_owner_id_foreign');
            $table->dropForeign('projects_client_id_foreign');
        });
        Schema::drop('projects');
    }
}
