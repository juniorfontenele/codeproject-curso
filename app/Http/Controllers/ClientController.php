<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Entities\Client;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;
use CodeProject\Http\Controllers\Controller;

class ClientController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function index()
    {
        return Client::all();
    }

   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Client::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Client::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $client = Client::find($id);
	    if (!$client) {
		    return response()->json(['message' => 'Usuário não encontrado!'], 404);
	    }
	    $client->fill($request->all());
	    if ($client->save()) {
		    return $client;
	    }
	    else {
	        return response()->json(['message' => 'Falha ao atualizar'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::find($id);
	    if (!$client) {
		    return response()->json(['message' => 'Usuário não encontrado!'],404);
	    }
	    if ($client->delete()) {
		    return response()->json(['message' => 'Usuário excluído com sucesso!'],200);
	    }
	    else {
	        return response()->json(['message' => 'Falha ao excluir usuário!'],500);
        }
    }
}
