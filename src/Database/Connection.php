<?php

namespace App\Database;

class Connection
{
    private const OPTIONS = [
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_CASE => \PDO::CASE_NATURAL
    ];

    private static ?\PDO $pdo = null;
    private function __construct() {}

    public static function getInstance()
    {
        if(is_null(self::$pdo)) {
            self::$pdo = new \PDO(
                "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD'],
                self::OPTIONS
            );
            return self::$pdo;
        }
    }
}