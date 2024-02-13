<?php

namespace App\rabbitmq;

use PhpAmqpLib\Message\AMQPMessage;

class RabbitmqManager
{
    private static array $properties = [
        'content_type' => 'application/json',
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
    ];

    private static ?AMQPMessage $message = null;

    public static function publishMessage(string $queueName, string $message)
    {
        $connection = Connection::getInstance();
        $channel = $connection->channel();
        $channel->queue_declare($queueName, auto_delete: false);
        $message = self::$message ?
            self::$message->setBody($message) :
            new AMQPMessage($message, self::$properties);
        $channel->basic_publish($message, '', $queueName);
        $channel->close();
        $connection->close();
    }

    public static function setAMQPMessage(array $properties = [])
    {
        self::$message = new AMQPMessage("", self::$properties ?? $properties);
    }
}