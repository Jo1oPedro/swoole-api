<?php

namespace Cascata\Framework\Http\route;

use Cascata\Framework\Container\Container;
use Cascata\Framework\Http\Response;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use Cascata\Framework\Http\Request;


class Router
{

    public function __invoke(Request $request): Response
    {
        [$handler, $vars] = [$request->server['requestHandler'], $request->server['requestVars']];

        if(is_array($handler)) {
            [$controllerId, $method] = $handler;

            $controller = Container::getInstance()->get($controllerId);
            $handler = [$controller, $method];
            $vars = $this->autoWireMethod($handler, $vars, $request);
            return call_user_func_array($handler, $vars);
        }

        $vars = $this->autoWireCallback($handler, $vars, $request);
        return $handler(...$vars);
    }

    private function autoWireMethod(array $handler, $vars, Request $request): array
    {
        $reflectionController = new ReflectionClass($handler[0]);
        $reflectionMethod = $reflectionController->getMethod($handler[1]);
        return $this->autoWire($reflectionMethod, $vars, $request);
    }

    private function autoWireCallback(callable $handler, $vars, Request $request): array
    {
        $reflectionFunction = new ReflectionFunction($handler);
        return $this->autoWire($reflectionFunction, $vars, $request);
    }

    private function autoWire(ReflectionMethod|ReflectionFunction $reflectionObject, array $vars, Request $request)
    {
        foreach($reflectionObject->getParameters() as $parameter) {
            if(array_key_exists($parameter->name, $vars)) {
                continue;
            }

            $parameterNamespace = $parameter->getType()->getName();

            if($parameterNamespace === 'Cascata\Framework\Http\Request') {
                $vars[$parameter->name] = $request;
                continue;
            }

            $reflectionClass = new ReflectionClass($parameterNamespace);
            if($reflectionClass->isSubclassOf('Cascata\Framework\Http\requestValidation\FormRequest')) {
                $formRequest = $reflectionClass->newInstance($request);
                $method = $reflectionClass->getMethod('validateRequest');
                $method->invoke($formRequest);
                $vars[$parameter->name] = $formRequest;
                continue;
            }

            $vars[$parameter->name] = Container::getInstance()->get($parameter->getType()->getName());
        }
        return $vars;
    }
}