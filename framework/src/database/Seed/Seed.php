<?php

namespace Cascata\Framework\database\Seed;

use Cascata\Framework\Container\Container;
use Symfony\Component\Console\Input\InputInterface;

class Seed
{
    public static function handle(InputInterface $input)
    {
        Container::getInstance()->get('db');
        $seedDir = scandir(BASE_PATH . "/database/seeders");
        $seedDir = array_slice($seedDir, 2);
        foreach ($seedDir as $seed) {
            $seed = str_replace(".php", "", $seed);
            $seedClass = new \ReflectionClass("Database\\seeders\\{$seed}");
            if($seedClass->isSubclassOf(SeederInterface::class)) {
                $method = $seedClass->getMethod('run');
                $method->invoke($seedClass->newInstance());
            }
        }
    }
}