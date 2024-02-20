<?php

declare(strict_types=1);

use Cascata\Framework\Bootstrap\Dependencies;
use Cascata\Framework\Bootstrap\Events;
use Symfony\Component\Dotenv\Dotenv;

include_once __DIR__ . '/vendor/autoload.php';

Co::set(['hook_flags', SWOOLE_HOOK_ALL]);

define("BASE_PATH", __DIR__);

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH . "/tests/.env");

require_once BASE_PATH . "/vendor/autoload.php";
require_once BASE_PATH . "/src/routes/api.php";

Dependencies::start();
Events::registerEvents();