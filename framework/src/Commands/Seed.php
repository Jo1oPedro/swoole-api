<?php

namespace Cascata\Framework\Commands;

use Cascata\Framework\database\Seed\SeederInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Seed extends Command
{
    protected static string $name = "db:seed";

    protected static string $defaultDescription = "Seed the database";

    protected function configure(): void
    {
        $this
            ->setHelp(self::$defaultDescription)
            ->setDescription(self::$defaultDescription)
            ->setName(self::$name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seedDir = scandir(BASE_PATH . "/database/seeders");
        $seedDir = array_slice($seedDir, 2);
        foreach ($seedDir as $seed) {
            $output->writeln("Running seed {$seed}");
            $seed = str_replace(".php", "", $seed);
            $seedClass = new \ReflectionClass("Database\\seeders\\{$seed}");
            if($seedClass->isSubclassOf(SeederInterface::class)) {
                $method = $seedClass->getMethod('run');
                $method->invoke($seedClass->newInstance());
            }
        }
        return Command::SUCCESS;
    }
}