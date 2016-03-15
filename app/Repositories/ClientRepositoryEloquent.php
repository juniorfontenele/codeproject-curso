<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 29/02/2016
 * Time: 18:21
 */

namespace CodeProject\Repositories;


use CodeProject\Entities\Client;
use Prettus\Repository\Eloquent\BaseRepository;

class ClientRepositoryEloquent extends BaseRepository implements ClientRepository
{

	public function model()
	{
		return Client::class;
	}

} 