<?php

namespace Cascata\Framework\Http\Middleware;

use Cascata\Framework\Http\Response;
use Cascata\Framework\Http\route\Router;
use Cascata\Framework\Http\Request;
use Respect\Validation\Exceptions\NestedValidationException;

class AnswerRequest implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        try {
            return (new Router())($request);
        } catch (NestedValidationException $exception) {
            return Response::badRequest($exception->getMessages()['allOf']);
        }
    }
}