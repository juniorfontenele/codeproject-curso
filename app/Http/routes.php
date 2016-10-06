<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

//OAuth2 Authentication
Route::post('/oauth/access_token', function(\LucaDegasperi\OAuth2Server\Authorizer $authorizer) {
    return Response::json($authorizer->issueAccessToken());
});

Route::group(['middleware' => 'oauth'], function() {

	//Clients
    Route::group(['prefix' => 'client'], function() {
        Route::get('/', 'ClientController@index');
        Route::post('/', 'ClientController@store');
        Route::get('/{id}', 'ClientController@show');
        Route::put('/{id}', 'ClientController@update');
        Route::delete('/{id}', 'ClientController@destroy');
    });

	//Projects
    Route::group(['prefix' => 'project'], function() {
        Route::group(['middleware' => 'CheckProjectPermission'], function() {
            //Project Tasks
            Route::get('/{id}/task', 'ProjectTaskController@getTasks');
            Route::post('/{id}/task', 'ProjectTaskController@addTask');
            Route::put('/{id}/task/{task_id}', 'ProjectTaskController@updateTask');
            Route::get('/{id}/task/{task_id}', 'ProjectTaskController@showTask');
            Route::delete('/{id}/task/{task_id}', 'ProjectTaskController@removeTask');

            //Project Notes
            Route::get('/{id}/note', 'ProjectNoteController@getNotes');
            Route::post('/{id}/note', 'ProjectNoteController@addNote');
            Route::put('/{id}/note/{note_id}', 'ProjectNoteController@updateNote');
            Route::get('/{id}/note/{note_id}', 'ProjectNoteController@showNote');
            Route::delete('/{id}/note/{note_id}', 'ProjectNoteController@removeNote');

            //Project Members
            Route::get('/{id}/member', 'ProjectMemberController@getMembers');
            Route::post('/{id}/member/{user_id}', ['middleware' => 'CheckProjectOwner', 'uses' => 'ProjectMemberController@addMember']);
            Route::get('/{id}/member/{user_id}', 'ProjectMemberController@isMember');
            Route::delete('/{id}/member/{user_id}', ['middleware' => 'CheckProjectOwner', 'uses' => 'ProjectMemberController@removeMember']);

	        //Project Files
	        Route::get('/{id}/file', 'ProjectFileController@getFiles');
	        Route::post('/{id}/file', 'ProjectFileController@addFile');
	        //Route::put('/{id}/file/{file_id}', 'ProjectFileController@updateFile');
	        Route::get('/{id}/file/{file_id}', 'ProjectFileController@showFile');
	        Route::delete('/{id}/file/{file_id}', 'ProjectFileController@removeFile');
        });

        //Project
        Route::get('/', 'ProjectController@index');
        Route::post('/', 'ProjectController@store');
        Route::get('/{id}', ['uses' => 'ProjectController@show', 'middleware' => 'CheckProjectPermission']);
        Route::put('/{id}', ['uses' => 'ProjectController@update', 'middleware' => 'CheckProjectOwner']);
        Route::delete('/{id}', ['uses' => 'ProjectController@destroy', 'middleware' => 'CheckProjectOwner']);
    });
});