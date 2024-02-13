<?php

use App\email\Email;
use App\rabbitmq\Connection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . "/../../vendor/autoload.php";

$dotEnv = new Dotenv();
$dotEnv->load(__DIR__ . "/../../apiEnvs/.env");

$connection = Connection::getInstance();
$channel = $connection->channel();
$channel->queue_declare('user_registered', auto_delete: false);
$channel->basic_consume('user_registered', callback: function (AMQPMessage $message) {
    $email = new Email();
    $email->send(
        [
            'address' => 'jpppedreira@gmail.com',
            'name' => 'cascata'
        ],
        [
            'address' => $message->getBody(),
            'name' => 'cascata'
        ],
        'OIIIIIIIIIII',
        'oi do cascata'
    );
    $message->ack();
});

try {
    $channel->consume();
} catch (Throwable $throwable) {
    var_dump($throwable);
}