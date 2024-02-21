<?php

namespace Cascata\Framework\Bootstrap;

use Symfony\Component\Console\Application;

class Command
{
    public static function processCommands(): void
    {
        $application = new Application();

        $frameworkCommands = array_slice(scandir(__DIR__ . "/../Commands"), 2);

        foreach($frameworkCommands as $command) {
            $command = str_replace(".php", "", $command);
            $application->add(new ("Cascata\Framework\Commands\\{$command}")());
        }

        $userCommands = array_slice(scandir(BASE_PATH . "/src/commands"), 2);

        foreach($userCommands as $command) {
            $command = str_replace(".php", "", $command);
            $application->add(new ("App\commands\\{$command}")());
        }

        $application->run();
    }
}