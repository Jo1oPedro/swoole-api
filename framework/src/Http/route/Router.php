<?php

namespace Cascata\Framework\Http\route;

use Cascata\Framework\Container\GlobalContainer;
use Cascata\Framework\Http\Response;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use ReflectionClass;
use Swoole\Http\Request;


class Router
{
    private Dispatcher $dispatcher;
    public function __construct(
        private RouteCollector $routerGrouper
    ) {
      $this->dispatcher = new Dispatcher\GroupCountBased($this->routerGrouper->getData());
    }

    public function __invoke(Request $request): Response
    {
        $routeInfo = $this->extractRouteInfo($request);

        if(!is_array($routeInfo)) {
            return $routeInfo;
        }

        [$handler, $vars] = $routeInfo;

        if(is_array($handler)) {
            [$controllerId, $method] = $handler;

            $controller = GlobalContainer::getInstance()->get($controllerId);
            $handler = [$controller, $method];
            $vars = $this->autoWireMethod($handler, $vars, $request);
            return call_user_func_array($handler, $vars);
        }

        $vars = $this->autoWireCallback($handler, $vars, $request);
        return $handler($request);
    }

    private function extractRouteInfo(Request $request): Response|array
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(),
            $request->server['request_uri']
        );

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return Response::notFound(
                    ['Content-Type' => 'application/json'],
                    'Not found'
                );
            case Dispatcher::METHOD_NOT_ALLOWED:
                return Response::methodNotAllowed(
                    ['Contenty-type' => 'application/json'],
                    'Method not allowed'
                );
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            default:
                return Response::internalServerError("");
        }
    }

    private function autoWireMethod($handler, $vars, Request $request): array
    {
        $reflectionController = new ReflectionClass($handler[0]);
        $reflectionMethod = $reflectionController->getMethod($handler[1]);
        foreach($reflectionMethod->getParameters() as $parameter) {
            if(array_key_exists($parameter->name, $vars)) {
                continue;
            }

            $parameterNamespace = $parameter->getType()->getName();

            if($parameterNamespace === 'Swoole\Http\Request') {
                $vars[$parameter->name] = $request;
                continue;
            }

            $reflectionClass = new ReflectionClass($parameterNamespace);
            if($reflectionClass->isSubclassOf('App\requests\FormRequest')) {
                $formRequest = $reflectionClass->newInstance($request);
                $method = $reflectionClass->getMethod('validateRequest');
                $method->invoke($formRequest);
                $vars[$parameter->name] = $formRequest;
                continue;
            }

            $vars[$parameter->name] = GlobalContainer::getInstance()->get($parameter->getType()->getName());
        }
        return $vars;
    }

    private function autoWireCallback($handler, $vars, Request $request): array
    {
        $reflectionFunction = new \ReflectionFunction($handler);
        foreach($reflectionFunction->getParameters() as $parameter) {
            if(array_key_exists($parameter->name, $vars)) {
                continue;
            }

            $parameterNamespace = $parameter->getType()->getName();

            if($parameterNamespace === 'Swoole\Http\Request') {
                $vars[$parameter->name] = $request;
                continue;
            }

            $reflectionClass = new ReflectionClass($parameterNamespace);
            if($reflectionClass->isSubclassOf('App\requests\FormRequest')) {
                $formRequest = $reflectionClass->newInstance($request);
                $method = $reflectionClass->getMethod('validateRequest');
                $method->invoke($formRequest);
                $vars[$parameter->name] = $formRequest;
                continue;
            }

            $vars[$parameter->name] = GlobalContainer::getInstance()->get($parameter->getType()->getName());
        }
        return $vars;
    }
}