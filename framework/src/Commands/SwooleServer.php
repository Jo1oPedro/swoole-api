<?php

namespace Cascata\Framework\Commands;

use Cascata\Framework\Http\Middleware\RequestHandler;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SwooleServer extends Command
{
    protected static string $name = 'swoole-server';

    protected static string $defaultDescription = 'Starts the swoole server';

    protected function configure(): void
    {
        $this
            ->setHelp(self::$defaultDescription)
            ->setDescription(self::$defaultDescription)
            ->setName(self::$name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->start($io);

        return Command::SUCCESS;
    }

    private function start(SymfonyStyle $io): void
    {
        $server = new Server('0.0.0.0', (int) $_ENV['PORT']);

        $server->on('start', function (Server $server) use($io) {
            $io->success('HTTP Server ready at http://localhost:' . $_ENV['PORT']);
        });

        $server->on('request', function (Request $request, Response $response) {
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

        $server->start();
    }
}