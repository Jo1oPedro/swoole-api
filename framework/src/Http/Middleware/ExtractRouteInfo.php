<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Http\Response;
use Cascata\Framework\Http\route\Route;
use FastRoute\Dispatcher;
use Cascata\Framework\Http\Request;

class ExtractRouteInfo implements MiddlewareInterface
{
    private Dispatcher $dispatcher;

    public function __construct(
        private Route $routerGrouper
    ) {
        $this->dispatcher = new Dispatcher\GroupCountBased($this->routerGrouper->getData());
    }

    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->server['request_uri']
        );

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return Response::notFound(
                    ['Content-Type' => 'application/json'],
                    'Route not found'
                );
            case Dispatcher::METHOD_NOT_ALLOWED:
                return Response::methodNotAllowed(
                    ['Contenty-type' => 'application/json'],
                    'Http method not allowed'
                );
            case Dispatcher::FOUND:
                $request->server = array_merge(
                    $request->server,
                    [
                        'requestHandler' => $routeInfo[1],
                        'requestVars' => $routeInfo[2]
                    ]
                );
                if(!is_callable($routeInfo[1])) {
                    $next->injectMiddleware($routeInfo[1][2]);
                }
                return $next->handle($request);
            default:
                return Response::internalServerError("");
        }
    }
}