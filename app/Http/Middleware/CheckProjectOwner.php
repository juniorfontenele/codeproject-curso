<?php

namespace CodeProject\Http\Middleware;

use Closure;
use CodeProject\Entities\User;
use CodeProject\Exceptions\CodeProjectException;
use CodeProject\Services\ProjectService;
use Illuminate\Support\MessageBag;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class CheckProjectOwner
{

    /**
     * @var ProjectService
     */
    protected $service;

    /**
     * CheckProjectOwner constructor.
     * @param ProjectService $service
     */
    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws CodeProjectException
     */
    public function handle($request, Closure $next)
    {
        $user_id = Authorizer::getResourceOwnerId();
        if (!$this->service->isOwner($user_id,$request->id)) {
            throw new CodeProjectException(new MessageBag(['forbidden' => 'Acesso negado']),403);
        }
        return $next($request);
    }
}
