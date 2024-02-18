<?php

namespace Cascata\Framework\Bootstrap;

use Cascata\Framework\events\EventInterface;
use Cascata\Framework\events\Events as EventRegister;

class Events
{
    public static function registerEvents()
    {
        $eventDir = array_slice(scandir(BASE_PATH . "/src/events"), 2);
        $event = EventRegister::getInstance();

        foreach($eventDir as $eventFile) {
            $eventFile = str_replace(".php", "", $eventFile);
            $class = new \ReflectionClass("App\\events\\{$eventFile}");
            if($class->isSubclassOf(EventInterface::class)) {
                $classInstance = $class->newInstance();
                $event->addListener(
                    $classInstance::class,
                    $classInstance
                );
            }
        }
    }
}