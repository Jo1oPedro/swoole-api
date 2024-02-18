<?php

use Symfony\Component\Dotenv\Dotenv;

include_once __DIR__ . '/vendor/autoload.php';

const ROOT_DIR = __DIR__;

$dotenv = new Dotenv();
$dotenv->load();