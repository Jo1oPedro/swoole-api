<?php

namespace Cascata\Framework\Bootstrap;

use App\Database\Connection;
use Cascata\Framework\Container\Container;
use Cascata\Framework\Http\route\Route;
use Illuminate\Database\Capsule\Manager;
use Memcached;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Dependencies
{
    public static function start()
    {
        $container = Container::getInstance();
        self::registerLogger($container);
        self::registerRoute($container);
        self::registerPDO($container);
        self::registerMemcached($container);
        self::registerDbCapsule($container);
    }

    private static function registerLogger(Container $container)
    {
        $container->set('logger', function () {
            $logger = new Logger('app');
            $logger->pushHandler(new StreamHandler(BASE_PATH . "/" . $_ENV['LOG_STORAGE']));
            return $logger;
        });
    }

    private static function registerRoute(Container $container)
    {
        $container->set(Route::class, function () {
            return Route::getInstance();
        });
    }

    private static function registerPDO(Container $container)
    {
        /*$container->set(PDO::class, function () {
            return Connection::getInstance();
        });*/
    }

    private static function registerMemcached(Container $container)
    {
        $memcached = new Memcached();
        $memcached->addServer('banco_de_dados_em_memoria', 10211);
        $container->set(Memcached::class, $memcached);
    }

    private static function registerDbCapsule(Container $container)
    {
        $container->set('db', function () {
            $capsule = new Manager();
            $capsule->addConnection([
                'driver' => $_ENV['DB_DRIVER'],
                'host' => 'banco_de_dados_relacional',
                'database' => $_ENV['DB_DATABASE'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
            ]);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
        });
    }
}