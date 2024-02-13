<?php

namespace App\rabbitmq;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Connection
{
    private static ?AMQPStreamConnection $instance = null;

    private function __construct() {}

    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new AMQPStreamConnection(
                'mensageria',
                5672,
                'cascata',
                'cascata12'
            );
        }
        return self::$instance;
    }
}