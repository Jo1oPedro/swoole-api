<?php

namespace Cascata\Framework\Bootstrap;

use Cascata\Framework\Container\Container;
use Cascata\Framework\Http\Middleware\RequestHandler;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use Swoole\Table;
use Swoole\Timer;

class SwooleServer
{
    private static Server $server;

    public static function start()
    {
        self::$server = new Server('0.0.0.0', (int) $_ENV['PORT']);
        self::onStart();
        self::onRequest();
        self::$server->start();
    }

    private static function onStart()
    {
        self::$server->on('start', function (Server $server) {
            echo 'HTTP Server ready at http://localhost:' . $_ENV['PORT'] . PHP_EOL;
        });
    }

    private static function onRequest()
    {
        self::$server->on('request', function (Request $request, Response $response) {
            if($request->server['request_uri'] === '/favicon.ico') {
                $response->end('');
                return;
            };

            $requestWrapper = new \Cascata\Framework\Http\Request($request);
            $requestHandler = new RequestHandler();

            list(
                $statusCode,
                $headers,
                $content
                ) = $requestHandler->handle($requestWrapper)->toArray();

            $response->setStatusCode($statusCode ?? 200);
            foreach($headers as $header => $value) {
                $response->setHeader($header, $value);
            }
            $response->end($content);

            /*if(isset($request->server['query_string'])) {
                parse_str($request->server['query_string'], $params);
            }*/
        });
    }
}