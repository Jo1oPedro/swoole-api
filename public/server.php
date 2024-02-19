<?php

declare(strict_types=1);

use Cascata\Framework\Bootstrap\Command;
use Cascata\Framework\Bootstrap\Dependencies;
use Cascata\Framework\Bootstrap\Events;
use Cascata\Framework\Bootstrap\SwooleServer;

Co::set(['hook_flags', SWOOLE_HOOK_ALL]);

define("BASE_PATH", dirname(__DIR__));

require_once BASE_PATH . "/vendor/autoload.php";
require_once BASE_PATH . "/src/routes/api.php";

Dependencies::start();
Events::registerEvents();
/*if(Command::processCommands()) {
    return;
}*/
SwooleServer::start();