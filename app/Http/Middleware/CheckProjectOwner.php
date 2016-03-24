<?php

namespace CodeProject\Http\Middleware;

use Closure;
use CodeProject\Exceptions\CodeProjectException;
use CodeProject\Services\ProjectService;
use Illuminate\Support\MessageBag;
use LucaDegasperi\OAuth2Server\Authorizer;

class CheckProjectOwner
{

    /**
     * @var ProjectService
     */
    protected $service;
    /**
     * @var Authorizer
     */
    protected $authorizer;

    /**
     * CheckProjectOwner constructor.
     * @param ProjectService $service
     * @param Authorizer $authorizer
     */
    public function __construct(ProjectService $service, Authorizer $authorizer)
    {
        $this->service = $service;
        $this->authorizer = $authorizer;
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
        $user_id = $this->authorizer->getResourceOwnerId();
        if (!$this->service->isOwner($user_id,$request->id)) {
            throw new CodeProjectException(new MessageBag(['forbidden' => 'Acesso negado']),403);
        }
        return $next($request);
    }
}
