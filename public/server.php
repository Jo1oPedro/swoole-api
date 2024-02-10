<?php

use Cascata\Framework\Http\route\Router;
use Cascata\Framework\Http\route\RouterGrouper;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

define("BASE_PATH", dirname(__DIR__));

require_once BASE_PATH . "/vendor/autoload.php";
require_once BASE_PATH . "/src/http/web/routes.php";
require_once BASE_PATH . "/config/services.php";

$server = new Server('0.0.0.0', '9999');

$server->on('start', function (Server $server) {
    echo 'HTTP Server ready at http://localhost:9999' . PHP_EOL;
});

$server->on('request', function (Request $request, Response $response) {
    if($request->server['request_uri'] === '/favicon.ico') {
       $response->end('');
       return;
    };

    list($statusCode, $headers, $content) = (new Router(RouterGrouper::getInstance()))($request);

    $response->setStatusCode($statusCode ?? 200);
    foreach($headers as $header => $value) {
        $response->setHeader($header, $value);
    }
    $response->end($content);

    /*if(isset($request->server['query_string'])) {
        parse_str($request->server['query_string'], $params);
    }*/
});

$server->start();