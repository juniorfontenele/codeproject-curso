<?php
/**
 * Author: Junior Fontenele <lcbfjr@gmail.com>
 * Date: 29/02/2016
 * Time: 18:21
 */

namespace CodeProject\Repositories;


use CodeProject\Entities\Client;
use CodeProject\Entities\User;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{

	public function model()
	{
		return User::class;
	}

} 