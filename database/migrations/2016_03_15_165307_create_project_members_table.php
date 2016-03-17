<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_members', function (Blueprint $table) {
            $table->integer('project_id')->unasigned();
            $table->integer('user_id')->unasigned();

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->primary(['project_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_members', function(Blueprint $table) {
            $table->dropForeign('project_members_project_id_foreign');
            $table->dropForeign('project_members_user_id_foreign');
        });
        Schema::drop('project_members');
    }
}
