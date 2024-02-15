<?php

use Cascata\Framework\Http\Middleware\RequestHandler;
use Psr\Container\ContainerInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use Symfony\Component\Dotenv\Dotenv;

Co::set(['hook_flags', SWOOLE_HOOK_ALL]);

define("BASE_PATH", dirname(__DIR__));

require_once BASE_PATH . "/vendor/autoload.php";
require_once BASE_PATH . "/src/routes/api.php";

/** @var ContainerInterface $container */
$container = require_once BASE_PATH . "/config/services.php";

$dotEnv = new Dotenv();
$dotEnv->load(BASE_PATH . "/apiEnvs/.env");

$server = new Server('0.0.0.0', (int) $_ENV['PORT']);

$server->on('start', function (Server $server) {
    echo 'HTTP Server ready at http://localhost:' . $_ENV['PORT'] . PHP_EOL;
});

$server->on('request', function (Request $request, Response $response) {
    if($request->server['request_uri'] === '/favicon.ico') {
       $response->end('');
       return;
    };
    $requestHandler = new RequestHandler();

    list(
        $statusCode,
        $headers,
        $content
    ) = $requestHandler->handle($request)->toArray();

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